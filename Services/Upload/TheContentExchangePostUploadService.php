<?php


namespace TheContentExchange\Services\Upload;

use JsonException;
use TheContentExchange\Exceptions\TheContentExchangeConfigurationException;
use TheContentExchange\Exceptions\TheContentExchangeConnectionException;
use TheContentExchange\Exceptions\TheContentExchangeCopyrightUsageException;
use TheContentExchange\Exceptions\TheContentExchangeUploadFailedException;
use TheContentExchange\Exceptions\TheContentExchangeWordPressPostIncompleteException;
use TheContentExchange\Exceptions\TheContentExchangeWordPressPostUnpublishedException;
use TheContentExchange\Services\TCE\TheContentExchangeSessionService;
use TheContentExchange\Services\WP\TheContentExchangeWpHttpService;
use TheContentExchange\Services\WP\TheContentExchangeWpPostService;

/**
 * Class TheContentExchangePostUploadService
 * @package TheContentExchange\Services\Upload
 */
class TheContentExchangePostUploadService
{
    /**
     * @var TheContentExchangeSessionService
     */
    private $tceSessionService;

    /**
     * @var TheContentExchangePostConversionService
     */
    private $postConversionService;

    /**
     * @var TheContentExchangeWpHttpService
     */
    private $httpService;

    /**
     * @var TheContentExchangeWpPostService
     */
    private $wpPostService;

    /**
     * TheContentExchangePostUploadService constructor.
     *
     * @param TheContentExchangeSessionService $tceSessionService
     * @param TheContentExchangeWpHttpService $httpService
     * @param TheContentExchangePostConversionService $postConversionService
     * @param TheContentExchangeWpPostService $wpPostService
     */
    public function __construct(
        TheContentExchangeSessionService $tceSessionService,
        TheContentExchangeWpHttpService $httpService,
        TheContentExchangePostConversionService $postConversionService,
        TheContentExchangeWpPostService $wpPostService
    ) {
        $this->tceSessionService = $tceSessionService;
        $this->httpService = $httpService;
        $this->postConversionService = $postConversionService;
        $this->wpPostService = $wpPostService;
    }

    /**
     * @param int $postId
     *
     * @throws JsonException
     * @throws TheContentExchangeConfigurationException
     * @throws TheContentExchangeConnectionException
     * @throws TheContentExchangeCopyrightUsageException
     * @throws TheContentExchangeUploadFailedException
     * @throws TheContentExchangeWordPressPostIncompleteException
     * @throws TheContentExchangeWordPressPostUnpublishedException
     */
    public function tceUploadWpPost(int $postId): void
    {
        // If not refreshToken or access code, throw exception
        if (!$this->tceSessionService->tceGetRefreshToken() && !$this->tceSessionService->tceGetAccessCode()) {
            throw new TheContentExchangeConnectionException("TCE Sharing - You are not connected to the TCE platform");
        }

        // Always refresh ID/Access token
        $this->tceSessionService->tceGetNewJwtTokens();

        // Convert post to TCE format
        // If post incomplete: notify user and quit
        $postInTceFormat = $this->postConversionService->tceConvertToApiFormatJson($postId);
        $url = TheContentExchangeSessionService::THE_CONTENT_EXCHANGE_API_ENDPOINT .
               TheContentExchangeSessionService::THE_CONTENT_EXCHANGE_UPLOAD_PATH;
        $tceUploadResponse = $this->httpService->tceRemoteRequest($url, $this->tceCreateUploadArgs($postInTceFormat));

        // If failure try again
        if (202 !== $tceUploadResponse->tceGetResponseCode()) {
            $tceUploadResponse = $this->httpService->tceRemoteRequest($url, $this->tceCreateUploadArgs($postInTceFormat));
        }

        // If still failure: something went wrong
        // quit
        if (202 !== $tceUploadResponse->tceGetResponseCode()) {
            throw new TheContentExchangeUploadFailedException($tceUploadResponse->tceGetResponseMessage());
        }

        // Success: Update meta info of post, add post upload status
        $this->wpPostService->tceSetIsShared($postId, 'true');
    }

    /**
     * @param string $postJson
     * @return mixed[]
     */
    private function tceCreateUploadArgs(string $postJson): array
    {
        return [
            'method' => 'POST',
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->tceSessionService->tceGetIdToken()
            ],
            'body' => $postJson
        ];
    }
}

<?php


namespace TheContentExchange\Controllers;

use TheContentExchange\Services\Filters\TheContentExchangeSanitizeOptionService;
use TheContentExchange\Services\TheContentExchangeServiceFactory;
use TheContentExchange\Services\TCE\TheContentExchangeConfigurationService;
use TheContentExchange\Services\TCE\TheContentExchangeSessionService;
use TheContentExchange\Services\WP\TheContentExchangeWpUrlService;

/**
 * Class TheContentExchangeConfigurationController
 * @package TheContentExchange\Controllers
 */
class TheContentExchangeConfigurationController
{
    /**
     * @var TheContentExchangeConfigurationService
     */
    private $tceConfigurationService;

  /**
   * @var TheContentExchangeSessionService
   */
    private $tceSessionService;

    /**
     * @var TheContentExchangeWpUrlService
     */
    private $wpUrlService;

    /**
     * @var TheContentExchangeSanitizeOptionService
     */
    private $tceSanitizeOptionService;

    /**
     * TheContentExchangeConfigurationController constructor.
     *
     * @param TheContentExchangeServiceFactory $serviceFactory
     */
    public function __construct(TheContentExchangeServiceFactory $serviceFactory)
    {
        $this->tceConfigurationService = $serviceFactory->tceCreateConfigurationService();
        $this->tceSessionService = $serviceFactory->tceCreateSessionService();
        $this->wpUrlService = $serviceFactory->tceCreateWpUrlService();
        $this->tceSanitizeOptionService = $serviceFactory->tceCreateSanitizeOptionService();
    }

    /**
     * Store the TCE configuration to the database.
     */
    public function tceStore(): void
    {
        try {
            $accessToken = $this->tceGetRequestAccessToken();
            $idToken = $this->tceGetRequestIdToken();
            $refreshToken =  $this->tceGetRequestRefreshToken();
            $organisationName = $this->tceGetRequestOrganisationName();
            $organisationId = $this->tceGetRequestOrganisationId();
            $sourceKey = $this->tceGetRequestSourceKey();
            $sourceName = $this->tceGetRequestSourceName();
            $autoUpdateDefault = $this->tceGetRequestAutoUploadDefault();
            $attachmentCopyrightHolderValue = $this->tceGetDefaultAttachmentCopyright();
            $attachmentCopyrightUsageValue = $this->tceGetCopyrightUsage();

            if (null === $organisationName
                || null === $organisationId
                || null === $accessToken
                || null === $idToken
                || null === $refreshToken
                || null === $sourceKey
                || null === $sourceName
                || null === $attachmentCopyrightHolderValue
                || null === $attachmentCopyrightUsageValue
            ) {
                http_response_code(500);
                echo $this->wpUrlService->tceGetCustomWpAdminPageUrl('tce-sharing-configuration');
                exit();
            }

            $this->tceSessionService->tceUpdateAccessCode($accessToken);
            $this->tceSessionService->tceUpdateIdToken($idToken);
            $this->tceSessionService->tceUpdateRefreshToken($refreshToken);
            $this->tceConfigurationService->tceUpdateOrganisationId($organisationId);
            $this->tceConfigurationService->tceUpdateOrganisationName($organisationName);
            $this->tceConfigurationService->tceUpdateSourceKey($sourceKey);
            $this->tceConfigurationService->tceUpdateSourceName($sourceName);
            $this->tceConfigurationService->tceUpdateAutoUploadDefault($autoUpdateDefault);
            $this->tceConfigurationService->tceUpdateDefaultAttachmentCopyright($attachmentCopyrightHolderValue);
            $this->tceConfigurationService->tceUpdateCopyrightUsage($attachmentCopyrightUsageValue);

            // @todo update setting for each post where metadata is not set yet.

            http_response_code();
            echo $this->wpUrlService->tceGetCustomWpAdminPageUrl('tce-sharing');
            exit();
        } catch (\Exception $exception) {
            http_response_code(500);
            echo $this->wpUrlService->tceGetCustomWpAdminPageUrl('tce-sharing-configuration');
            exit("Something went wrong saving the configuration");
        }
    }

    /**
     * Retrieve 'accessToken' from POST request.
     */
    private function tceGetRequestAccessToken(): ?string
    {
        return $this->tceSanitizeOptionService->tceSanitizeOptionAccessToken() ?: null;
    }

    /**
     * Retrieve 'idToken' from POST request.
     */
    private function tceGetRequestIdToken(): ?string
    {
        return $this->tceSanitizeOptionService->tceSanitizeOptionIdToken() ?: null;
    }

    /**
     * Retrieve 'refreshToken' from POST request.
     */
    private function tceGetRequestRefreshToken(): ?string
    {
        return $this->tceSanitizeOptionService->tceSanitizeOptionRefreshToken() ?: null;
    }

    /**
     * Retrieve 'organisationName' from POST request.
     */
    private function tceGetRequestOrganisationName(): ?string
    {
        return $this->tceSanitizeOptionService->sanitizeOptionOrganisationName() ?: null;
    }

    /**
     * Retrieve 'organisationId' from POST request.
     */
    private function tceGetRequestOrganisationId(): ?string
    {
        return $this->tceSanitizeOptionService->tceSanitizeOptionOrganisationId() ?: null;
    }

    /**
     * Retrieve 'sourceKey' from POST request.
     */
    private function tceGetRequestSourceKey(): ?string
    {
        return $this->tceSanitizeOptionService->tceSanitizeOptionSourceKey() ?: null;
    }

    /**
     * Retrieve 'sourceName' from GET request.
     */
    private function tceGetRequestSourceName(): ?string
    {
        return $this->tceSanitizeOptionService->tceSanitizeOptionSourceName() ?: null;
    }

    /**
     * Retrieve 'autoUploadDefault' from POST request.
     *
     * @return string - Returns "on" or "off" according to whether the value is set or not.
     */
    private function tceGetRequestAutoUploadDefault(): string
    {
        return $this->tceSanitizeOptionService->tceSanitizeOptionAutoUploadDefault();
    }

    /**
     * Retrieve 'defaultAttachmentCopyright' from POST request.
     */
    private function tceGetDefaultAttachmentCopyright(): ?string
    {
        return $this->tceSanitizeOptionService->tceSanitizeOptionDefaultAttachmentCopyright() ?: null;
    }

    /**
     * Retrieve 'copyrightUsage' from POST request.
     *
     * @return string - Returns "on" or "off" according to whether the value is set or not.
     */
    private function tceGetCopyrightUsage(): string
    {
        return $this->tceSanitizeOptionService->tceSanitizeOptionCopyrightUsage();
    }
}

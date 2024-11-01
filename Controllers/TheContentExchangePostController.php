<?php

namespace TheContentExchange\Controllers;

use TheContentExchange\Services\Filters\TheContentExchangeInputFilterService;
use TheContentExchange\Services\Filters\TheContentExchangeSanitizeOptionService;
use TheContentExchange\Services\TheContentExchangeServiceFactory;
use TheContentExchange\Services\TCE\TheContentExchangePostService;

/**
 * Class TheContentExchangePostController
 * @package TheContentExchange\Controllers
 */
class TheContentExchangePostController
{
    /**
     * @var TheContentExchangePostService
     */
    private $tcePostService;

    /**
     * @var TheContentExchangeInputFilterService
     */
    private $tceInputFilterService;

    /**
     * @var TheContentExchangeSanitizeOptionService
     */
    private $tceSanitizeOptionService;

    /**
     * TheContentExchangePostController constructor.
     *
     * @param TheContentExchangeServiceFactory $serviceFactory
     */
    public function __construct(TheContentExchangeServiceFactory $serviceFactory)
    {
        $this->tcePostService = $serviceFactory->tceCreatePostService();
        $this->tceInputFilterService = $serviceFactory->tceCreateInputFilterService();
        $this->tceSanitizeOptionService = $serviceFactory->tceCreateSanitizeOptionService();
    }

    /**
     * Update the 'autoUpload' value for a post.
     *
     * @param int|string $postId
     */
    public function tceUpdateAutoUploadPost($postId): void
    {
        // If this function is called with an ajax-call, the post ID is not passed as a parameter.
        // Therefore the post ID must be retrieved from the request.
        $postId = $postId ? $postId : $this->tceGetRequestPostId();

        if ($postId) {
            // Get the autoUploadPost value from the request.
            $autoUpdatePost = $this->tceGetRequestAutoUploadPost();
            $this->tcePostService->tceSetAutoUploadPost($postId, $autoUpdatePost);
        }
    }

    /**
     * Retrieve 'postId' from POST request.
     */
    private function tceGetRequestPostId(): ?int
    {
        return $this->tceInputFilterService->tceFilterPostInput('postId', FILTER_SANITIZE_NUMBER_INT) ?: null;
    }

    /**
     * Retrieve 'autoUploadPost' from GET request.
     *
     * @return string - Returns "on" or "off" according to whether the value is set or not.
     */
    private function tceGetRequestAutoUploadPost(): string
    {
        return $this->tceSanitizeOptionService->tceSanitizeOptionAutoUploadPost() ?? "off";
    }
}

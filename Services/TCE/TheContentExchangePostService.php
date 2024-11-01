<?php


namespace TheContentExchange\Services\TCE;

use TheContentExchange\WpWrappers\WpData\TheContentExchangeWpPostWrapper;
use TheContentExchange\WpWrappers\TheContentExchangeWpWrapperFactory;

/**
 * Class TheContentExchangePostService
 * @package TheContentExchange\Services\TCE
 */
class TheContentExchangePostService
{
    /**
     * @var TheContentExchangeWpPostWrapper
     */
    private $wpPostWrapper;

    /**
     * TheContentExchangePostService constructor.
     *
     * @param TheContentExchangeWpWrapperFactory $wpWrapperFactory
     */
    public function __construct(TheContentExchangeWpWrapperFactory $wpWrapperFactory)
    {
        $this->wpPostWrapper = $wpWrapperFactory->tceCreateWpPostWrapper();
    }

    /**
     * Update 'autoUploadPost' value.
     *
     * @param $postId
     * @param $value
     */
    public function tceSetAutoUploadPost($postId, $value): bool
    {
        return $this->wpPostWrapper->tceAddPostMeta($postId, 'autoUploadPost', $value);
    }

    /**
     * Get 'autoUploadPost' value.
     *
     * @param $postId
     */
    public function tceGetAutoUploadPost($postId): string
    {
        return $this->wpPostWrapper->tceGetPostMeta($postId, 'autoUploadPost');
    }
}

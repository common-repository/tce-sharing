<?php


namespace TheContentExchange\Services\WP;

use TheContentExchange\Models\TheContentExchangeHttpResponse;
use TheContentExchange\WpWrappers\WpHttp\TheContentExchangeWpRedirectWrapper;
use TheContentExchange\WpWrappers\WpHttp\TheContentExchangeWpSafeRemoteRequestWrapper;
use TheContentExchange\WpWrappers\TheContentExchangeWpWrapperFactory;

/**
 * Class TheContentExchangeWpHttpService
 * @package TheContentExchange\Services\WP
 */
class TheContentExchangeWpHttpService
{
    /**
     * @var TheContentExchangeWpWrapperFactory
     */
    private $wpWrapperFactory;

    /**
     * @var TheContentExchangeWpRedirectWrapper
     */
    private $wpRedirectWrapper;

    /**
     * @var TheContentExchangeWpSafeRemoteRequestWrapper
     */
    private $wpSafeRemoteRequestWrapper;

    /**
     * TheContentExchangeWpHttpService constructor.
     *
     * @param TheContentExchangeWpWrapperFactory $wpWrapperFactory
     */
    public function __construct(TheContentExchangeWpWrapperFactory $wpWrapperFactory)
    {
        $this->wpWrapperFactory = $wpWrapperFactory;
        $this->wpRedirectWrapper = $this->wpWrapperFactory->tceCreateWpRedirectWrapper();
        $this->wpSafeRemoteRequestWrapper = $this->wpWrapperFactory->tceCreateWpSafeRemoteRequestWrapper();
    }

    /**
     * @param string $url
     */
    public function tceRedirectTo(string $url): void
    {
        $this->wpRedirectWrapper->tceRedirectTo($url);
    }

    /**
     * @param string $url
     * @param mixed[] $requestArgs
     *
     * @return TheContentExchangeHttpResponse
     */
    public function tceRemoteRequest(string $url, array $requestArgs): TheContentExchangeHttpResponse
    {
        return $this->wpSafeRemoteRequestWrapper->tceRemoteRequest($url, $requestArgs);
    }
}

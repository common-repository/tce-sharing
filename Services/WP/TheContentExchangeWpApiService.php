<?php


namespace TheContentExchange\Services\WP;

use TheContentExchange\WpWrappers\WpHttp\TheContentExchangeWpRestRouteWrapper;
use TheContentExchange\WpWrappers\TheContentExchangeWpWrapperFactory;

/**
 * Class TheContentExchangeWpApiService
 * @package TheContentExchange\Services\WP
 */
class TheContentExchangeWpApiService
{
    /**
     * @var TheContentExchangeWpWrapperFactory
     */
    private $wpWrapperFactory;

    /**
     * @var TheContentExchangeWpRestRouteWrapper
     */
    private $wpRestRouteWrapper;

    /**
     * TheContentExchangeWpApiService constructor.
     *
     * @param TheContentExchangeWpWrapperFactory $wpWrapperFactory
     */
    public function __construct(TheContentExchangeWpWrapperFactory $wpWrapperFactory)
    {
        $this->wpWrapperFactory = $wpWrapperFactory;
        $this->wpRestRouteWrapper = $this->wpWrapperFactory->tceCreateWpRestRouteWrapper();
    }

    /**
     * @param string $path
     * @param mixed $callback
     * @param string $httpMethod
     * @return void
     */
    public function tceDefineRoute(string $path, $callback, string $httpMethod): void
    {
        $this->wpRestRouteWrapper->tceDefineRoute($path, $callback, $httpMethod);
    }
}

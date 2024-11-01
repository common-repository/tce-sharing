<?php


namespace TheContentExchange\Services\WP;

use TheContentExchange\WpWrappers\WpFiles\TheContentExchangeWpFilePathWrapper;
use TheContentExchange\WpWrappers\TheContentExchangeWpWrapperFactory;

/**
 * Class TheContentExchangeWpPathService
 * @package TheContentExchange\Services\WP
 */
class TheContentExchangeWpPathService
{
    /**
     * @var TheContentExchangeWpWrapperFactory
     */
    private $wpWrapperFactory;

    /**
     * @var TheContentExchangeWpFilePathWrapper
     */
    private $wpFilePathWrapper;

    /**
     * TheContentExchangeWpPathService constructor.
     *
     * @param TheContentExchangeWpWrapperFactory $wpWrapperFactory
     */
    public function __construct(TheContentExchangeWpWrapperFactory $wpWrapperFactory)
    {
        $this->wpWrapperFactory = $wpWrapperFactory;
        $this->wpFilePathWrapper = $this->wpWrapperFactory->tceCreateWpFilePathWrapper();
    }

    /**
     * @param string $viewName
     * @param string $partialName
     */
    public function tceGetPartialPath(string $viewName, string $partialName): string
    {
        return $this->wpFilePathWrapper->tceGetPartialPath($viewName, $partialName);
    }
}

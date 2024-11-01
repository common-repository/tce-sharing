<?php

namespace TheContentExchange\WpWrappers\WpFiles;

/**
 * Class TheContentExchangeWpFilePathWrapper
 * @package TheContentExchange\WpWrappers\WpFiles
 */
class TheContentExchangeWpFilePathWrapper
{
    /**
     * @param string $viewName
     * @param string $partialName
     */
    public function tceGetPartialPath(string $viewName, string $partialName): string
    {
        return THE_CONTENT_EXCHANGE_BASE_PATH . "Views/" . $viewName . "/partials/" . $partialName . ".php";
    }
}

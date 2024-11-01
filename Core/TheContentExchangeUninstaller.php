<?php


namespace TheContentExchange\Core;

use TheContentExchange\Services\TheContentExchangeServiceFactory;

/**
 * Class TheContentExchangeUninstaller
 * @package TheContentExchange\Core
 */
class TheContentExchangeUninstaller
{
    /**
     * Uninstall the TCE WP Plugin.
     */
    public static function tceUninstall(): void
    {
        $serviceFactory = new TheContentExchangeServiceFactory();

        // Initialize database entries for configuration options
        $serviceFactory->tceCreateConfigurationService()->tceDeleteOptions();
        $serviceFactory->tceCreateSessionService()->tceDeleteSessionOptions();
    }
}

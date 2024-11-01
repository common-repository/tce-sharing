<?php


namespace TheContentExchange\Core;

use TheContentExchange\Services\TheContentExchangeServiceFactory;

/**
 * Class TheContentExchangeDeactivator
 * @package TheContentExchange\Core
 */
class TheContentExchangeDeactivator
{
    /**
     * De-activate the TCE WP Plugin.
     */
    public function tceDeactivate(): void
    {
        $serviceFactory = new TheContentExchangeServiceFactory();

        // Delete database entries for TCE session options
        $serviceFactory->tceCreateSessionService()->tceDeleteSessionOptions();
        $serviceFactory->tceCreateConfigurationService()->tceDeleteOptions();
    }
}

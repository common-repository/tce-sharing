<?php


namespace TheContentExchange\Core;

use TheContentExchange\Services\TheContentExchangeServiceFactory;

/**
 * Class TheContentExchangeActivator
 * @package TheContentExchange\Core
 */
class TheContentExchangeActivator
{
    /**
     * Activate the TCE WP Plugin.
     */
    public function tceActivate(): void
    {
        $serviceFactory = new TheContentExchangeServiceFactory();

        // Initialize database entries for configuration options
        $serviceFactory->tceCreateConfigurationService()->tceInitOptions();
        $serviceFactory->tceCreateSessionService()->tceInitSessionOptions();
        $serviceFactory->tceCreateWpPostService()->tceUpdateSharedPostsItemMetaId();
    }
}

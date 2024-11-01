<?php


namespace TheContentExchange\Controllers;

use TheContentExchange\Services\TheContentExchangeServiceFactory;
use TheContentExchange\Services\TCE\TheContentExchangeSessionService;
use TheContentExchange\Services\TCE\TheContentExchangeConfigurationService;
use TheContentExchange\Services\WP\TheContentExchangeWpHttpService;
use TheContentExchange\Services\WP\TheContentExchangeWpUrlService;

/**
 * Class TheContentExchangeSessionController
 * @package TheContentExchange\Controllers
 */
class TheContentExchangeSessionController implements TheContentExchangeWpController
{
    /**
     * @var TheContentExchangeWpUrlService
     */
    private $wpUrlService;

    /**
     * @var TheContentExchangeSessionService
     */
    private $tceSessionService;

  /**
   * @var TheContentExchangeConfigurationService
   */
    private $tceConfigurationService;

    /**
     * @var TheContentExchangeWpHttpService
     */
    private $httpService;

    /**
     * TheContentExchangeSessionController constructor.
     *
     * @param TheContentExchangeServiceFactory $serviceFactory
     */
    public function __construct(TheContentExchangeServiceFactory $serviceFactory)
    {
        $this->wpUrlService = $serviceFactory->tceCreateWpUrlService();
        $this->tceConfigurationService = $serviceFactory->tceCreateConfigurationService();
        $this->tceSessionService = $serviceFactory->tceCreateSessionService();
        $this->httpService = $serviceFactory->tceCreateWpHttpService();
    }


    public function tceRegisterRoutes(): void
    {
    }

    public function tceRemoveSessionDetails(): void
    {
        $this->tceSessionService->tceDeleteSessionOptions();
        $this->tceConfigurationService->tceDeleteOptions();

        // Redirect to main plugin page
        $redirectUrl = $this->wpUrlService->tceGetCustomWpAdminPageUrl('tce-sharing-configuration');
        $redirectUrl = $this->wpUrlService->tceAddQueryArgsToUrl(['auth-status' => 'disconnected'], $redirectUrl);
        $this->httpService->tceRedirectTo($redirectUrl);
    }
}

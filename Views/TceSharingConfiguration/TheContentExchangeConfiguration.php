<?php


namespace TheContentExchange\Views\TceSharingConfiguration;

use TheContentExchange\Services\Filters\TheContentExchangeInputFilterService;
use TheContentExchange\Services\Notices\TheContentExchangeConfigurationNoticesService;
use TheContentExchange\Services\TheContentExchangeServiceFactory;
use TheContentExchange\Services\TCE\TheContentExchangeConfigurationService;
use TheContentExchange\Services\TCE\TheContentExchangeSessionService;
use TheContentExchange\Services\WP\TheContentExchangeWpPathService;
use TheContentExchange\Services\WP\TheContentExchangeWpUrlService;
use TheContentExchange\Services\WP\TheContentExchangeWpViewService;
use TheContentExchange\Views\TheContentExchangeWpAdminPage;

/**
 * Class TheContentExchangeConfiguration
 * @package TheContentExchange\Views\TceSharingConfiguration
 */
class TheContentExchangeConfiguration implements TheContentExchangeWpAdminPage
{
    /**
     * @var TheContentExchangeServiceFactory
     */
    private $serviceFactory;

    /**
     * @var TheContentExchangeWpViewService
     */
    private $viewService;

    /**
     * @var TheContentExchangeConfigurationService
     */
    private $configurationService;

    /**
     * @var TheContentExchangeSessionService
     */
    private $tceSessionService;

    /**
     * @var TheContentExchangeWpUrlService
     */
    private $wpUrlService;

    /**
     * @var TheContentExchangeWpPathService
     */
    private $wpPathService;

    /**
     * @var TheContentExchangeConfigurationNoticesService
     */
    private $tceConfigurationNoticesService;
    /**
     * @var string
     */
    private $viewName = "TceSharingConfiguration";

    /**
     * @var string
     */
    private $wpPageId = "tce-sharing_page_tce-sharing-configuration";

    /**
     * @var TheContentExchangeInputFilterService
     */
    private $tceInputFilterService;

    /**
     * TheContentExchangeConfiguration constructor.
     *
     * @param TheContentExchangeServiceFactory $serviceFactory
     */
    public function __construct(TheContentExchangeServiceFactory $serviceFactory)
    {
        $this->serviceFactory = $serviceFactory;
        $this->configurationService = $this->serviceFactory->tceCreateConfigurationService();
        $this->tceSessionService = $this->serviceFactory->tceCreateSessionService();
        $this->viewService = $this->serviceFactory->tceCreateWpViewService();
        $this->wpUrlService = $this->serviceFactory->tceCreateWpUrlService();
        $this->wpPathService = $this->serviceFactory->tceCreateWpPathService();
        $this->tceConfigurationNoticesService = $this->serviceFactory->tceCreateConfigurationNoticesService();
        $this->tceInputFilterService = $this->serviceFactory->tceCreateInputFilterService();
    }

    public function tceEnqueueStyles(): void
    {
        if ($this->tcePageIsActive()) {
            $this->viewService->tceEnqueueStyleSheet(
                'tce-sharing-configuration-form',
                $this->wpUrlService->tceGetCssFileUrl($this->viewName, "configuration-form")
            );
        }
    }

    public function tceEnqueueScripts(): void
    {
        if ($this->tcePageIsActive()) {
            $this->viewService->tceEnqueueScript(
                'configuration-ajax-handler',
                $this->wpUrlService->tceGetJsFileUrl($this->viewName, "ajax-handler"),
                ['jquery']
            );

            $params = [
                'wpAjaxUrl' => $this->wpUrlService->tceGetWpAdminAjaxUrl()
            ];

            $this->viewService->tceLocalize('configuration-ajax-handler', 'params', $params);
            $this->viewService->tceEnqueueScript(
                'tce.main.bundle',
                $this->wpUrlService->tceGetJsFileUrl($this->viewName, "tce.main.bundle"),
                [],
                true
            );
        }
    }

    public function tceRegisterPage(): void
    {
        $this->viewService->tceAddSubMenuPage(
            'tce-sharing',
            'TCE Sharing Configuration',
            'Configuration',
            'manage_options',
            'tce-sharing-configuration',
            [$this, 'tceRenderPage']
        );
    }

    public function tceRenderPage(): void
    {
        require $this->wpPathService->tceGetPartialPath($this->viewName, "configuration");
    }

    private function tcePageIsActive(): bool
    {
        return $this->viewService->tceCheckCurrentPage($this->wpPageId);
    }

    public function tceNotifyConfigurationError(): void
    {
        $configurationStatus = $this->tceInputFilterService->tceFilterGetInput('configuration-status', FILTER_SANITIZE_STRING);
        if ($this->tcePageIsActive() && "failed" === $configurationStatus) {
            $this->tceConfigurationNoticesService->tceAddError(
                "TCE Sharing - An error occurred while saving configuration. Please try again."
            );
            $this->tceConfigurationNoticesService->tceShowNotices();
        }
    }
}

<?php


namespace TheContentExchange\Views\TceSharingMain;

use TheContentExchange\Services\Filters\TheContentExchangeInputFilterService;
use TheContentExchange\Services\Notices\TheContentExchangeAuthenticationNoticesService;
use TheContentExchange\Services\Notices\TheContentExchangeConfigurationNoticesService;
use TheContentExchange\Services\TheContentExchangeServiceFactory;
use TheContentExchange\Services\TCE\TheContentExchangeConfigurationService;
use TheContentExchange\Services\TCE\TheContentExchangeSessionService;
use TheContentExchange\Services\WP\TheContentExchangeWpPathService;
use TheContentExchange\Services\WP\TheContentExchangeWpUrlService;
use TheContentExchange\Services\WP\TheContentExchangeWpUserService;
use TheContentExchange\Services\WP\TheContentExchangeWpViewService;
use TheContentExchange\Views\TheContentExchangeWpAdminPage;

/**
 * Class TheContentExchangeMain
 * @package TheContentExchange\Views
 */
class TheContentExchangeMain implements TheContentExchangeWpAdminPage
{
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
     * @var TheContentExchangeWpPathService
     */
    private $wpPathService;

    /**
     * @var TheContentExchangeWpUrlService
     */
    private $wpUrlService;

    /**
     * @var TheContentExchangeWpUserService
     */
    private $wpUserService;

    /**
     * @var TheContentExchangeAuthenticationNoticesService
     */
    private $tceAuthenticationNoticesService;

    /**
     * @var TheContentExchangeConfigurationNoticesService
     */
    private $tceConfigurationNoticesService;

    /**
     * @var string
     */
    private $viewName = "TceSharingMain";

    /**
     * @var TheContentExchangeInputFilterService
     */
    private $tceInputFilterService;

    /**
     * TheContentExchangeMain constructor.
     *
     * @param TheContentExchangeServiceFactory $serviceFactory
     */
    public function __construct(TheContentExchangeServiceFactory $serviceFactory)
    {
        $this->configurationService = $serviceFactory->tceCreateConfigurationService();
        $this->wpPathService = $serviceFactory->tceCreateWpPathService();
        $this->tceSessionService = $serviceFactory->tceCreateSessionService();
        $this->wpUrlService = $serviceFactory->tceCreateWpUrlService();
        $this->viewService = $serviceFactory->tceCreateWpViewService();
        $this->wpUserService = $serviceFactory->tceCreateWpUserService();
        $this->tceAuthenticationNoticesService = $serviceFactory->tceCreateAuthenticationNoticesService();
        $this->tceConfigurationNoticesService = $serviceFactory->tceCreateConfigurationNoticesService();
        $this->tceInputFilterService = $serviceFactory->tceCreateInputFilterService();
    }

    public function tceEnqueueStyles(): void
    {
        if ($this->tcePageOrSubPageIsActive()) {
            $this->viewService->tceEnqueueStyleSheet(
                'tce-sharing-card',
                $this->wpUrlService->tceGetCssFileUrl($this->viewName, "tce-sharing-card")
            );
        }
    }

    public function tceEnqueueScripts(): void
    {
        if ($this->tcePageOrSubPageIsActive()) {
            $this->viewService->tceEnqueueScript(
                'tce-sharing-main',
                $this->wpUrlService->tceGetJsFileUrl($this->viewName, "tce-sharing-main")
            );
        }
    }

    /**
     * @return void
     */
    public function tceRegisterPage(): void
    {
        $this->viewService->tceAddMenuPage(
            'tce-sharing-main',
            'TCE Sharing',
            'publish_posts',
            'tce-sharing',
            [$this, 'tceRenderPage'],
            $this->tceGetIcon(),
            69
        );
    }

    /**
     * @return void
     */
    public function tceRenderPage(): void
    {
        require $this->wpPathService->tceGetPartialPath($this->viewName, 'view-header');
        $connected = $this->tceSessionService->tceIsConnected();
        $configured = $this->configurationService->tceIsConfigured();

        if ($this->wpUserService->tceCheckUserRight('manage_options')) {
            if (!$connected || !$configured) { // If the plugin is not configured, show configure button
                require $this->wpPathService->tceGetPartialPath($this->viewName, "plugin-not-configured");
            }
        }

        if ($connected && $configured) {
            require $this->wpPathService->tceGetPartialPath($this->viewName, 'plugin-connected');
            require $this->wpPathService->tceGetPartialPath($this->viewName, 'plugin-help');
        }

        require $this->wpPathService->tceGetPartialPath($this->viewName, 'view-footer');
    }

    private function tcePageIsActive(): bool
    {
        return $this->viewService->tceCheckCurrentPage('tce-sharing');
    }

    private function tcePageOrSubPageIsActive(): bool
    {
        $thisPageIsActive = $this->tcePageIsActive();
        $configurationPageIsActive = $this->viewService
            ->tceCheckCurrentPage("tce-sharing_page_tce-sharing-configuration");

        return $thisPageIsActive || $configurationPageIsActive;
    }

    public function tceNotifyAuthenticationStatus(): void
    {
        $authStatus = $this->tceInputFilterService->tceFilterGetInput('auth-status', FILTER_SANITIZE_STRING);
        if (false === ($this->tcePageIsActive() && "" !== $authStatus)) {
            return;
        }
        
        switch ($authStatus) {
            case 'success':
                $this->tceAuthenticationNoticesService->tceAddSuccess(
                    "TCE Sharing - You are now connected to the TCE platform"
                );
                break;
            case 'disconnected':
                $this->tceAuthenticationNoticesService->tceAddInfo(
                    "TCE Sharing - You are now disconnected from TCE platform"
                );
                break;
            default:
                return;
        }
        $this->tceAuthenticationNoticesService->tceShowNotices();
    }

    public function tceNotifyConfigurationUpdate(): void
    {
        $configurationStatus = $this->tceInputFilterService->tceFilterGetInput('configuration-status', FILTER_SANITIZE_STRING);
        if ($this->tcePageIsActive() && "failed" === $configurationStatus) {
            $this->tceConfigurationNoticesService->tceAddError(
                "TCE Sharing - An error occurred while saving configuration. Please try again."
            );

            $this->tceConfigurationNoticesService->tceShowNotices();
        }
    }

    private function tceGetIcon(): string
    {
        // phpcs:disable Generic.Files.LineLength
        $icon = '<?xml version="1.0" encoding="utf-8"?><svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <g clip-path="url(#clip0)">
                        <path d="M14.9183 8.81979L14.0435 9.41568C11.6925 11.0209 9.76431 13.2031 8.43189 15.7666C7.05072 18.4342 6.36197 21.4251 6.43268 24.4487C6.50339 27.4722 7.33115 30.4253 8.83542 33.021C10.3397 35.6167 12.4693 37.7666 15.0172 39.2616C17.565 40.7567 20.4445 41.546 23.3755 41.5528C25.0502 41.5515 26.7155 41.2955 28.3178 40.793C31.5325 39.7915 34.3869 37.8281 36.5195 35.1516C38.6521 32.4751 39.9671 29.2058 40.298 25.7576C40.6288 22.3095 39.9607 18.8376 38.3781 15.7816C36.7955 12.7255 34.3697 10.2227 31.4077 8.59007L30.4831 8.07935L28.8097 11.3046L29.7343 11.8154C32.3264 13.2467 34.3915 15.5163 35.6128 18.2757C36.8341 21.0351 37.1439 24.1318 36.4948 27.0905C35.8457 30.0492 34.2734 32.7063 32.0194 34.6539C29.7654 36.6015 26.9543 37.7319 24.0175 37.8717C21.0808 38.0115 18.1807 37.153 15.7625 35.4278C13.3443 33.7025 11.5417 31.2061 10.6313 28.3216C9.72096 25.4372 9.75317 22.3242 10.7231 19.4605C11.6929 16.5967 13.5469 14.1406 16.0002 12.4691L16.875 11.8716L14.9183 8.81979Z" fill="white"/>
                        <path d="M23.4918 14.3274C21.4114 14.3304 19.3918 15.052 17.7551 16.3774C16.1184 17.7027 14.9589 19.5552 14.4615 21.6397C13.9641 23.7242 14.1574 25.9206 15.0107 27.8785C15.8639 29.8364 17.3279 31.4432 19.1692 32.4425C21.0104 33.4419 23.123 33.7763 25.1699 33.3924C27.2169 33.0086 29.0803 31.9286 30.4631 30.3246C31.8459 28.7207 32.6685 26.6852 32.7995 24.5427C32.9306 22.4002 32.3626 20.2742 31.1863 18.5035L31.0306 18.2738L30.6617 17.7759L30.162 17.2137L29.6141 16.6805L29.4055 16.5038C27.7367 15.0959 25.6474 14.327 23.4918 14.3274ZM23.4918 30.0217C23.4031 30.0217 23.3144 30.0217 23.2257 30.0217C22.259 29.978 21.3177 29.6892 20.4851 29.1806C19.6524 28.6721 18.9541 27.9595 18.452 27.106C17.9498 26.2526 17.6593 25.2846 17.6062 24.2877C17.5531 23.2908 17.7391 22.2958 18.1475 21.3907C18.556 20.4856 19.1744 19.6984 19.948 19.0987C20.7216 18.4991 21.6266 18.1054 22.5828 17.9527C23.539 17.8 24.5169 17.893 25.43 18.2232C26.3431 18.5535 27.1632 19.111 27.8177 19.8463L27.838 19.8688C28.6034 20.7393 29.1077 21.8201 29.2898 22.9803C29.4719 24.1405 29.324 25.3303 28.864 26.4056C28.404 27.4808 27.6517 28.3954 26.6982 29.0383C25.7447 29.6813 24.6309 30.0251 23.4918 30.0281V30.0217Z" fill="white"/>
                        <path d="M23.1681 1.55088e-05C18.5113 -0.00108057 13.9623 1.44585 10.1143 4.15209C7.59477 5.90336 5.43816 8.1544 3.77002 10.7741C2.10188 13.3938 0.955552 16.3298 0.39781 19.4111C-1.98538 32.3941 6.37833 45.0157 19.043 47.5487C21.8616 48.1197 24.7578 48.1513 27.5874 47.6419C30.8275 47.0554 33.9092 45.7625 36.6267 43.8496C39.1461 42.0982 41.3026 39.8471 42.9707 37.2275C44.6388 34.6078 45.7852 31.6718 46.3431 28.5906L46.5377 27.5305L43.0929 26.8415L42.8968 27.9096C42.2306 31.5444 40.6074 34.9197 38.2051 37.6656C30.917 45.9971 18.3332 46.6428 10.1516 39.1032C8.19228 37.3127 6.59799 35.1376 5.46163 32.7048C4.32528 30.272 3.66959 27.6301 3.5328 24.9332C3.38603 22.279 3.75285 19.6212 4.6118 17.1148C5.47075 14.6083 6.80468 12.3034 8.53582 10.3344C15.8255 2.00301 28.4093 1.35732 36.5894 8.88884L37.3677 9.6052L39.7181 6.91801L38.9398 6.19357C35.7668 3.26674 31.8855 1.28033 27.7073 0.444939C26.2115 0.147242 24.6915 -0.00175009 23.1681 1.55088e-05Z" fill="white"/>
                    </g>
                    <defs>
                        <clipPath id="clip0">
                            <rect width="106" height="48" fill="white"/>
                        </clipPath>
                    </defs>
                </svg>';
        // phpcs:enable Generic.Files.LineLength

        return 'data:image/svg+xml;base64,' . base64_encode($icon);
    }
}

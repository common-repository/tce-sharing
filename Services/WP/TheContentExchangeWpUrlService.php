<?php


namespace TheContentExchange\Services\WP;

use TheContentExchange\WpWrappers\WpFiles\TheContentExchangeWpFileUrlWrapper;
use TheContentExchange\WpWrappers\TheContentExchangeWpWrapperFactory;

/**
 * Class TheContentExchangeWpUrlService
 * @package TheContentExchange\Services
 */
class TheContentExchangeWpUrlService
{
    /**
     * @var TheContentExchangeWpWrapperFactory
     */
    private $wpWrapperFactory;

    /**
     * @var TheContentExchangeWpFileUrlWrapper
     */
    private $wpFileUrlWrapper;

    /**
     * TheContentExchangeWpUrlService constructor.
     *
     * @param TheContentExchangeWpWrapperFactory $wpWrapperFactory
     */
    public function __construct(TheContentExchangeWpWrapperFactory $wpWrapperFactory)
    {
        $this->wpWrapperFactory = $wpWrapperFactory;
        $this->wpFileUrlWrapper = $wpWrapperFactory->tceCreateWpFileUrlWrapper();
    }

    /**
     * @param mixed[] $args
     * @param string $url
     * @return string
     */
    public function tceAddQueryArgsToUrl(array $args, string $url): string
    {
        return $this->wpFileUrlWrapper->tceAddQueryArgsToUrl($args, $url);
    }

    /**
     * @param string $viewName
     * @param string $cssFileName
     */
    public function tceGetCssFileUrl(string $viewName, string $cssFileName): string
    {
        return $this->wpFileUrlWrapper->tceGetCssFileUrl($viewName, $cssFileName);
    }

    /**
     * @param string $viewName
     * @param string $jsFileName
     */
    public function tceGetJsFileUrl(string $viewName, string $jsFileName): string
    {
        return $this->wpFileUrlWrapper->tceGetJsFileUrl($viewName, $jsFileName);
    }

    /**
     * @param string $iconFileName
     */
    public function tceGetRootIconUrl(string $iconFileName): string
    {
        return $this->wpFileUrlWrapper->tceGetRootIconUrl($iconFileName);
    }

    public function tceGetWpAdminAjaxUrl(): string
    {
        return $this->wpFileUrlWrapper->tceGetWpAdminAjaxUrl();
    }

    public function tceGetWpAdminPostUrl(): string
    {
        return $this->wpFileUrlWrapper->tceGetWpAdminPostUrl();
    }

    public function tceGetWpAdminEditPostsUrl(): string
    {
        return $this->wpFileUrlWrapper->tceGetWpAdminEditPostsUrl();
    }

    /**
     * @param string $pageName
     * @return string
     */
    public function tceGetCustomWpAdminPageUrl(string $pageName): string
    {
        return $this->wpFileUrlWrapper->tceGetCustomWpAdminPageUrl($pageName);
    }
}

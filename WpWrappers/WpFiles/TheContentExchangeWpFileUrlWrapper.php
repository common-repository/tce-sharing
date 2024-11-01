<?php

namespace TheContentExchange\WpWrappers\WpFiles;

/**
 * Class TheContentExchangeWpFileUrlWrapper
 * @package TheContentExchange\WpWrappers\WpFiles
 */
class TheContentExchangeWpFileUrlWrapper
{
    /**
     * @param mixed[] $args
     * @param string $url
     * @return string
     */
    public function tceAddQueryArgsToUrl(array $args, string $url): string
    {
        return add_query_arg($args, $url);
    }

    /**
     * @param string $viewName
     * @param string $cssFileName
     */
    public function tceGetCssFileUrl(string $viewName, string $cssFileName): string
    {
        return $this->tceGetPluginBaseUrl() . "/Views/" . $viewName . "/css/" . $cssFileName . ".css";
    }

    /**
     * @param string $viewName
     * @param string $jsFileName
     */
    public function tceGetJsFileUrl(string $viewName, string $jsFileName): string
    {
        return $this->tceGetPluginBaseUrl() . "/Views/" . $viewName . "/js/" . $jsFileName . ".js";
    }

    /**
     * @param string $iconFileName
     */
    public function tceGetRootIconUrl(string $iconFileName): string
    {
        return $this->tceGetPluginBaseUrl() . "/" . $iconFileName . ".svg";
    }

    public function tceGetWpAdminAjaxUrl(): string
    {
        return admin_url('admin-ajax.php');
    }

    public function tceGetWpAdminPostUrl(): string
    {
        return admin_url('admin-post.php');
    }

    public function tceGetWpAdminEditPostsUrl(): string
    {
        return admin_url('edit.php');
    }

    /**
     * @param string $pageName
     */
    public function tceGetCustomWpAdminPageUrl(string $pageName): string
    {
        return admin_url('admin.php') . '?page=' . $pageName;
    }

    private function tceGetPluginBaseUrl(): string
    {
        return rtrim(THE_CONTENT_EXCHANGE_BASE_URL, "/");
    }
}

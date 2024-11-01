<?php


namespace TheContentExchange\WpWrappers\WpHttp;

/**
 * Class TheContentExchangeWpRedirectWrapper
 * @package TheContentExchange\WpWrappers\WpHttp
 */
class TheContentExchangeWpRedirectWrapper
{
    /**
     * @param string $url
     */
    public function tceRedirectTo(string $url): void
    {
        wp_safe_redirect($url);
        exit();
    }
}

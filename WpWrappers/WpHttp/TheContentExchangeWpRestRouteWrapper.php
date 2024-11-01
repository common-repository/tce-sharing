<?php


namespace TheContentExchange\WpWrappers\WpHttp;

/**
 * Class TheContentExchangeWpRestRouteWrapper
 * @package TheContentExchange\WpWrappers\WpHttp
 */
class TheContentExchangeWpRestRouteWrapper
{
    /**
     * Registers a route on the WordPress API.
     * It can be found on "<SITENAME>/wp-json/tce-sharing/v1/<PATH>"
     *
     * @param string $path
     * @param mixed $callback
     * @param string $httpMethod
     * @return void
     */
    public function tceDefineRoute($path, $callback, $httpMethod)
    {
        $namespace = 'tce-sharing/v1';

        register_rest_route(
            $namespace,
            $path,
            [
                'methods' => $httpMethod,
                'callback' => $callback,
                'permission_callback' => function () {
                    return true;
                },
            ]
        );
    }
}

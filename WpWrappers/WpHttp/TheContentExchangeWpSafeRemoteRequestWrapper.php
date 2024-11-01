<?php


namespace TheContentExchange\WpWrappers\WpHttp;

use TheContentExchange\Models\TheContentExchangeHttpResponse;
use WP_Error;

/**
 * Class TheContentExchangeWpSafeRemoteRequestWrapper
 * @package TheContentExchange\WpWrappers\WpHttp
 */
class TheContentExchangeWpSafeRemoteRequestWrapper
{
    /**
     * @param string $url
     * @param mixed[] $args
     */
    public function tceRemoteRequest(string $url, array $args): TheContentExchangeHttpResponse
    {
        $response = wp_safe_remote_request($url, $args);

        if ($response instanceof WP_Error) {
            $responseCode = $response->get_error_code();
            $responseMessage = $response->get_error_message();
            return new TheContentExchangeHttpResponse($responseCode, $responseMessage);
        }

        return $this->tceConvertToHttpResponse($response);
    }

    /**
     * @param mixed[] $response
     */
    private function tceConvertToHttpResponse(array $response): TheContentExchangeHttpResponse
    {
        $headers = wp_remote_retrieve_headers($response)->getAll();
        $body = wp_remote_retrieve_body($response);
        $responseCode = wp_remote_retrieve_response_code($response);
        $responseMessage = wp_remote_retrieve_response_message($response);

        return new TheContentExchangeHttpResponse($responseCode, $responseMessage, $headers, $body);
    }
}

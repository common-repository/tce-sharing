<?php


namespace TheContentExchange\Views;

/**
 * Interface TheContentExchangeWpAdminPage
 * @package TheContentExchange\Views
 */
interface TheContentExchangeWpAdminPage
{
    /**
     * Registers the page to WordPress.
     *
     * @return void
     */
    public function tceRegisterPage();

    /**
     * Renders the page.
     *
     * @return void
     */
    public function tceRenderPage();
}

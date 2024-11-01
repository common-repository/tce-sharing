<?php


namespace TheContentExchange\WpWrappers\WpViews;

/**
 * Class TheContentExchangeWpPageWrapper
 * @package TheContentExchange\Views\WpWrappers
 */
class TheContentExchangeWpPageWrapper
{
    /**
     * @param string $title             # Page Title
     * @param string $menuTitle         # Title displayed in the admin menu
     * @param string $capability        # Minimal rights required by the WP user to see the page
     * @param string $menuSlug          # Slug presented in url on page view
     * @param mixed $renderFunction     # Callback function that renders the page
     * @return void
     */
    public function tceAddOptionsPage($title, $menuTitle, $capability, $menuSlug, $renderFunction): void
    {
        add_options_page($title, $menuTitle, $capability, $menuSlug, $renderFunction);
    }

    /**
     * @param string $title             # Page Title
     * @param string $menuTitle         # Title displayed in the admin menu
     * @param string $capability        # Minimal rights required by the WP user to see the page
     * @param string $menuSlug          # Slug presented in url on page view
     * @param mixed  $renderFunction    # Callback function that renders the page
     * @param string $iconUrl           # Icon of the page
     * @param int    $position          # Position in the admin menu
     */
    public function tceAddMenuPage($title, $menuTitle, $capability, $menuSlug, $renderFunction, $iconUrl, $position): void
    {
        add_menu_page($title, $menuTitle, $capability, $menuSlug, $renderFunction, $iconUrl, $position);
    }

    /**
     * @param string $parentSlug        # Slug of parent page
     * @param string $title             # Page Title
     * @param string $menuTitle         # Title displayed in the admin menu
     * @param string $capability        # Minimal rights required by the WP user to see the page
     * @param string $menuSlug          # Slug presented in url on page view
     * @param mixed  $renderFunction    # Callback function that renders the page
     */
    public function tceAddSubMenuPage($parentSlug, $title, $menuTitle, $capability, $menuSlug, $renderFunction): void
    {
        add_submenu_page($parentSlug, $title, $menuTitle, $capability, $menuSlug, $renderFunction);
    }


    /**
     * @param string $fileName          # Name of the stylesheet, needs to be unique
     * @param string $filePath          # Path of the stylesheet
     * @return void
     */
    public function tceEnqueueStyleSheet($fileName, $filePath): void
    {
        wp_enqueue_style($fileName, $filePath);
    }

    /**
     * @param string $fileName # Name of the script, needs to be unique
     * @param string $filePath # Path of the script
     * @param string[] $dependencies
     * @param bool $inFooter
     */
    public function tceEnqueueScript(string $fileName, string $filePath, array $dependencies, bool $inFooter): void
    {
        wp_enqueue_script(
            $fileName,
            $filePath,
            $dependencies,
            THE_CONTENT_EXCHANGE_PLUGIN_VERSION,
            $inFooter
        );
    }

    /**
     * @param string $fileName
     * @param string $objectName
     * @param string[] $params
     */
    public function tceLocalize(string $fileName, string $objectName, array $params): void
    {
        wp_localize_script($fileName, $objectName, $params);
    }

    /**
     * @param string $pageId
     */
    public function tceCheckCurrentPage(string $pageId): bool
    {
        $screenId = get_current_screen()->id;

        return $screenId === $pageId || $screenId === 'toplevel_page_' . $pageId;
    }

    public function tceGetCurrentPageId(): int
    {
        return get_the_ID();
    }
}

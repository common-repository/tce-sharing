<?php


namespace TheContentExchange\Services\WP;

use TheContentExchange\WpWrappers\WpViews\TheContentExchangeWpPageWrapper;
use TheContentExchange\WpWrappers\TheContentExchangeWpWrapperFactory;

/**
 * Class TheContentExchangeWpViewService
 * @package TheContentExchange\Services\WP
 */
class TheContentExchangeWpViewService
{
    /**
     * @var TheContentExchangeWpWrapperFactory
     */
    private $wpWrapperFactory;

    /**
     * @var TheContentExchangeWpPageWrapper
     */
    private $wpPageWrapper;

    /**
     * TheContentExchangeWpViewService constructor.
     *
     * @param TheContentExchangeWpWrapperFactory $wpWrapperFactory
     */
    public function __construct(TheContentExchangeWpWrapperFactory $wpWrapperFactory)
    {
        $this->wpWrapperFactory = $wpWrapperFactory;
        $this->wpPageWrapper = $this->wpWrapperFactory->tceCreateWpPageWrapper();
    }

    /**
     * @param string $title             # Page Title
     * @param string $menuTitle         # Title displayed in the admin menu
     * @param string $capability        # Minimal rights required by the WP user to see the page
     * @param string $menuSlug          # Slug presented in url on page view
     * @param mixed $renderFunction    # Callback function that renders the page
     */
    public function tceAddSettingsPage($title, $menuTitle, $capability, $menuSlug, $renderFunction): void
    {
        $this->wpPageWrapper->tceAddOptionsPage($title, $menuTitle, $capability, $menuSlug, $renderFunction);
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
        $this->wpPageWrapper->tceAddMenuPage(
            $title,
            $menuTitle,
            $capability,
            $menuSlug,
            $renderFunction,
            $iconUrl,
            $position
        );
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
        $this->wpPageWrapper->tceAddSubMenuPage($parentSlug, $title, $menuTitle, $capability, $menuSlug, $renderFunction);
    }

    /**
     * @param string $fileName          # Name of the stylesheet, needs to be unique
     * @param string $filePath          # Path of the stylesheet
     * @return void
     */
    public function tceEnqueueStyleSheet($fileName, $filePath): void
    {
        $this->wpPageWrapper->tceEnqueueStyleSheet($fileName, $filePath);
    }

    /**
     * @param string $fileName # Name of the script, needs to be unique
     * @param string $filePath # Path of the script
     * @param string[] $dependencies
     * @param bool $inFooter
     */
    public function tceEnqueueScript(string $fileName, string $filePath, $dependencies = [], $inFooter = false): void
    {
        $this->wpPageWrapper->tceEnqueueScript($fileName, $filePath, $dependencies, $inFooter);
    }

    /**
     * @param string $fileName
     * @param string $objectName
     * @param string[] $params
     */
    public function tceLocalize(string $fileName, string $objectName, array $params): void
    {
        $this->wpPageWrapper->tceLocalize($fileName, $objectName, $params);
    }

    public function tceCheckCurrentPage(string $pageId): bool
    {
        return $this->wpPageWrapper->tceCheckCurrentPage($pageId);
    }

    public function tceGetCurrentPageId(): int
    {
        return $this->wpPageWrapper->tceGetCurrentPageId();
    }
}

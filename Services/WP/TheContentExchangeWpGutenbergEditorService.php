<?php


namespace TheContentExchange\Services\WP;

use TheContentExchange\WpWrappers\WpViews\TheContentExchangeWpGutenbergEditorWrapper;
use TheContentExchange\WpWrappers\TheContentExchangeWpWrapperFactory;

/**
 * Class TheContentExchangeWpGutenbergEditorService
 * @package TheContentExchange\Services\WP
 */
class TheContentExchangeWpGutenbergEditorService
{
    /**
     * @var TheContentExchangeWpWrapperFactory
     */
    private $wpWrapperFactory;

    /**
     * @var TheContentExchangeWpGutenbergEditorWrapper
     */
    private $wpGutenbergEditorWrapper;

    /**
     * TheContentExchangeWpGutenbergEditorService constructor.
     *
     * @param TheContentExchangeWpWrapperFactory $wpWrapperFactory
     */
    public function __construct(TheContentExchangeWpWrapperFactory $wpWrapperFactory)
    {
        $this->wpWrapperFactory = $wpWrapperFactory;
        $this->wpGutenbergEditorWrapper = $this->wpWrapperFactory->tceCreateWpGutenbergEditorWrapper();
    }

    /**
     * @param string $name
     * @param string $title
     * @param mixed[] $callback
     */
    public function tceAddSideMetaBox(string $name, string $title, array $callback): void
    {
        $this->wpGutenbergEditorWrapper->tceAddSideMetaBox($name, $title, $callback);
    }
}

<?php


namespace TheContentExchange\WpWrappers\WpViews;

/**
 * Class TheContentExchangeWpGutenbergEditorWrapper
 * @package TheContentExchange\WpWrappers\WpViews
 */
class TheContentExchangeWpGutenbergEditorWrapper
{
    /**
     * @param string $name
     * @param string $title
     * @param mixed[] $renderFunction
     */
    public function tceAddSideMetaBox(string $name, string $title, array $renderFunction): void
    {
        add_meta_box($name, $title, $renderFunction, 'post', 'side');
    }
}

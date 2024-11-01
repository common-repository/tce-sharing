<?php

namespace TheContentExchange\WpWrappers\WpNotices;

/**
 * Class TheContentExchangeWpNoticesWrapper
 * @package TheContentExchange\WpWrappers\WpNotices
 */
class TheContentExchangeWpNoticesWrapper
{
    /**
     * @param string $message # The formatted message text to display to the user
     * @param string $code # Slug-name to identify the error. Used as part of 'id' attribute in HTML output.
     * @param string $type # Message type, controls HTML class. Possible values include 'error', 'success', 'warning', 'info'.
     * @param string $setting # Slug title of the setting to which this error applies.
     */
    public function tceAddNotice(string $message, string $code, string $type, string $setting): void
    {
        add_settings_error($setting, $code, $message, $type);
    }

    /**
     * Part of the Settings API. Outputs a div for each error retrieved by get_settings_errors().
     *
     * If changes were just submitted ($_GET[‘settings-updated’]) and settings errors were saved to the ‘settings_errors’ transient
     * then those errors will be returned instead. This is used to pass errors back across pageloads.
     *
     * @param string $setting # Slug title of a specific setting whose errors you want.
     */
    public function tceShowNotices(string $setting): void
    {
        settings_errors($setting, true);
    }

    /**
     * Settings errors are stored in a transient for 30 seconds so that this transient can be retrieved on the next page load.
     */
    public function tceSetTransient(): void
    {
        if ($errors = get_settings_errors()) {
            set_transient('settings_errors', $errors, 30);
        }
    }

    /**
     * @param string $tag #The action to remove hooks from.
     * @param int[] $exceptedPriorities
     */
    public function tceClearNotices(string $tag, array $exceptedPriorities): void
    {
        global $plugin_page;
        if (empty($plugin_page) || false === strpos($plugin_page, 'tce-sharing')) {
            return;
        }

        global $wp_filter;

        if (key_exists($tag, $wp_filter) && !empty($exceptedPriorities)) {
            $callbacks = $wp_filter[$tag]->callbacks;
            array_map(function ($callback, $priority) use ($tag, $exceptedPriorities) {
                foreach ($callback as $functionName => $function) {
                    if (!in_array($priority, $exceptedPriorities) && false === strpos($functionName, 'tceNotify')) {
                        remove_all_actions($tag, $priority);
                    }
                }
            }, $callbacks, array_keys($callbacks));
        } else {
            // Warning! This will remove TCE notices too.
            remove_all_actions($tag);
        }
    }
}

<?php

namespace TheContentExchange\WpWrappers\WpFilters;

/**
 * Class TheContentExchangeWpSanitizeWrapper
 * @package TheContentExchange\WpWrappers\WpFilters
 */
class TheContentExchangeWpSanitizeWrapper
{

    /**
     * Sanitises various option values based on the nature of the option.
     * This is basically a switch statement which will pass $value through a number of functions depending on the $option.
     *
     * @param $option - The name of the option.
     * @param $value - The un-sanitised value.
     */
    public function tceSanitizeOptions($option, $value): ?string
    {
        return sanitize_option($option, $value);
    }

    /**
     * Sanitizes meta value.
     *
     * @param $metaKey - Metadata key
     * @param $metaValue - Metadata value to sanitize.
     */
    public function tceSanitizeMeta($metaKey, $metaValue): ?string
    {
        return sanitize_meta($metaKey, $metaValue, 'post');
    }
}

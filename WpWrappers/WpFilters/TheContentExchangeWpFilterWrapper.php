<?php

namespace TheContentExchange\WpWrappers\WpFilters;

/**
 * Class TheContentExchangeWpFilterWrapper
 * @package TheContentExchange\WpWrappers\WpFilters
 */
class TheContentExchangeWpFilterWrapper
{
    /**
     * Gets a specific external GET variable by name and optionally filters it.
     *
     * @param $varName - Name of a variable to get.
     * @param $filter - The ID of the filter to apply.
     *
     * @return mixed
     */
    public function tceFilterGetInput($varName, $filter)
    {
        return filter_input(INPUT_GET, $varName, $filter);
    }

    /**
     * Gets a specific external POST variable by name and optionally filters it.
     *
     * @param $varName - Name of a variable to get.
     * @param $filter - The ID of the filter to apply.
     *
     * @return mixed
     */
    public function tceFilterPostInput($varName, $filter)
    {
        return filter_input(INPUT_POST, $varName, $filter);
    }
}

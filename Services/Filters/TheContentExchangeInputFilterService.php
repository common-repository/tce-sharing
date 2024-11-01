<?php

namespace TheContentExchange\Services\Filters;

use TheContentExchange\WpWrappers\WpFilters\TheContentExchangeWpFilterWrapper;
use TheContentExchange\WpWrappers\TheContentExchangeWpWrapperFactory;

/**
 * Class TheContentExchangeInputFilterService
 * @package TheContentExchange\Services\Filters
 */
class TheContentExchangeInputFilterService
{
    /**
     * @var TheContentExchangeWpWrapperFactory
     */
    private $wrapperFactory;

    /**
     * @var TheContentExchangeWpFilterWrapper
     */
    private $wpFilterWrapper;


    /**
     * TheContentExchangeInputFilterService constructor.
     *
     * @param TheContentExchangeWpWrapperFactory $wrapperFactory
     */
    public function __construct(TheContentExchangeWpWrapperFactory $wrapperFactory)
    {
        $this->wrapperFactory = $wrapperFactory;
        $this->wpFilterWrapper = $this->wrapperFactory->tceCreateWpFilterWrapper();
    }

    /**
     * Gets a specific external GET variable by name and optionally filters it.
     *
     * @param $valueName - Name of a variable to get.
     * @param $filter - The ID of the filter to apply.
     *
     * @return mixed
     */
    public function tceFilterGetInput($valueName, $filter)
    {
        return $this->wpFilterWrapper->tceFilterGetInput($valueName, $filter);
    }

    /**
     * Gets a specific external POST variable by name and optionally filters it.
     *
     * @param $valueName - Name of a variable to get.
     * @param $filter - The ID of the filter to apply.
     *
     * @return mixed
     */
    public function tceFilterPostInput($valueName, $filter)
    {
        return $this->wpFilterWrapper->tceFilterPostInput($valueName, $filter);
    }
}

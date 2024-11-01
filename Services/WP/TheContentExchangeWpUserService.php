<?php


namespace TheContentExchange\Services\WP;

use TheContentExchange\WpWrappers\WpData\TheContentExchangeWpUserWrapper;
use TheContentExchange\WpWrappers\TheContentExchangeWpWrapperFactory;

/**
 * Class TheContentExchangeWpUserService
 * @package TheContentExchange\Services\WP
 */
class TheContentExchangeWpUserService
{
    /**
     * @var TheContentExchangeWpWrapperFactory
     */
    private $wpWrapperFactory;

    /**
     * @var TheContentExchangeWpUserWrapper
     */
    private $wpUserWrapper;

    /**
     * TheContentExchangeWpUserService constructor.
     *
     * @param TheContentExchangeWpWrapperFactory $wpWrapperFactory
     */
    public function __construct(TheContentExchangeWpWrapperFactory $wpWrapperFactory)
    {
        $this->wpWrapperFactory = $wpWrapperFactory;
        $this->wpUserWrapper = $this->wpWrapperFactory->tceCreateWpUserWrapper();
    }

    /**
     * @param string $right
     */
    public function tceCheckUserRight(string $right): bool
    {
        return $this->wpUserWrapper->tceCheckIfUserHasRightTo($right);
    }
}

<?php

namespace TheContentExchange\Services\Notices;

use TheContentExchange\WpWrappers\TheContentExchangeWpWrapperFactory;

/**
 * Class TheContentExchangeConfigurationNoticesService
 * @package TheContentExchange\Services\Notices
 */
class TheContentExchangeConfigurationNoticesService extends TheContentExchangeNoticesService
{
    /**
     * @var string
     */
    protected $name = 'notify-configuration-status';

    /**
     * TheContentExchangeConfigurationNoticesService constructor.
     *
     * @param TheContentExchangeWpWrapperFactory $wpWrapperFactory
     */
    public function __construct(TheContentExchangeWpWrapperFactory $wpWrapperFactory)
    {
        parent::__construct($wpWrapperFactory);
    }
}

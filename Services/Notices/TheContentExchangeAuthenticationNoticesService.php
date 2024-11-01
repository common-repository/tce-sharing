<?php

namespace TheContentExchange\Services\Notices;

use TheContentExchange\WpWrappers\TheContentExchangeWpWrapperFactory;

/**
 * Class TheContentExchangeAuthenticationNoticesService
 * @package TheContentExchange\Services\Notices
 */
class TheContentExchangeAuthenticationNoticesService extends TheContentExchangeNoticesService
{
    /**
     * @var string
     */
    protected $name = 'notify-authentication-status';

    /**
     * TheContentExchangeAuthenticationNoticesService constructor.
     *
     * @param TheContentExchangeWpWrapperFactory $wpWrapperFactory
     */
    public function __construct(TheContentExchangeWpWrapperFactory $wpWrapperFactory)
    {
        parent::__construct($wpWrapperFactory);
    }
}

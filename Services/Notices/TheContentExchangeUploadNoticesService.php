<?php

namespace TheContentExchange\Services\Notices;

use TheContentExchange\WpWrappers\TheContentExchangeWpWrapperFactory;

/**
 * Class TheContentExchangeUploadNoticesService
 * @package TheContentExchange\Services\Notices
 */
class TheContentExchangeUploadNoticesService extends TheContentExchangeNoticesService
{
    /**
     * @var string
     */
    protected $name = 'notify-upload-status';

    /**
     * TheContentExchangeUploadNoticesService constructor.
     *
     * @param TheContentExchangeWpWrapperFactory $wpWrapperFactory
     */
    public function __construct(TheContentExchangeWpWrapperFactory $wpWrapperFactory)
    {
        parent::__construct($wpWrapperFactory);
    }
}

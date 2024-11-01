<?php

namespace TheContentExchange\DTO;

/**
 * Class TheContentExchangePost
 *
 * Create a Data Transfer Object for WP_Post
 *
 * @package TheContentExchange\DTO
 */
class TheContentExchangePost
{
    /**
     * @var int
     */
    public $ID;

    /**
     * @var int
     */
    public $post_author = 0;

    /**
     * @var string
     */
    public $post_title = '';

    /**
     * @var string
     */
    public $post_date = '0000-00-00 00:00:00';

    /**
     * @var string
     */
    public $post_modified = '0000-00-00 00:00:00';

    /**
     * @var string
     */
    public $post_status = 'publish';

    /**
     * @var string
     */
    public $filter;
}

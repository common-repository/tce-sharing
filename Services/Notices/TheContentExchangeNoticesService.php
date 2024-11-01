<?php

namespace TheContentExchange\Services\Notices;

use TheContentExchange\WpWrappers\WpNotices\TheContentExchangeWpNoticesWrapper;
use TheContentExchange\WpWrappers\TheContentExchangeWpWrapperFactory;

/**
 * Class TheContentExchangeNoticesService
 * @package TheContentExchange\Services\Notices
 */
class TheContentExchangeNoticesService
{
    /**
     * @var TheContentExchangeWpWrapperFactory
     */
    private $wpWrapperFactory;

    /**
     * @var TheContentExchangeWpNoticesWrapper
     */
    private $wpNoticesWrapper;

    /**
     * @var string
     */
    private $settingIdentifier = 'tce-sharing-notices';

    /**
     * @var string
     */
    protected $name = 'tce-notify';

    /**
     * @var int[]
     */
    private $tceAdminNoticesPriorities = [999];

    /**
     * TheContentExchangeNoticesService constructor.
     *
     * @param TheContentExchangeWpWrapperFactory $wpWrapperFactory
     */
    public function __construct(TheContentExchangeWpWrapperFactory $wpWrapperFactory)
    {
        $this->wpWrapperFactory  = $wpWrapperFactory;
        $this->wpNoticesWrapper  = $this->wpWrapperFactory->tceCreateWpNoticesWrapper();
    }

    /**
     * Create a success notice.
     *
     * @param string $message
     * @param string $linkText
     * @param string $linkUrl
     */
    public function tceAddSuccess(string $message, string $linkText = '', string $linkUrl = ''): void
    {
        $this->tceAddNotice($message, 'success', $linkText, $linkUrl);
    }

    /**
     * Create an error notice.
     *
     * @param string $message
     * @param string $linkText
     * @param string $linkUrl
     */
    public function tceAddError(string $message, string $linkText = '', string $linkUrl = ''): void
    {
        $this->tceAddNotice($message, 'error', $linkText, $linkUrl);
    }

    /**
     * Create an info notice.
     *
     * @param string $message
     * @param string $linkText
     * @param string $linkUrl
     */
    public function tceAddInfo(string $message, string $linkText = '', string $linkUrl = ''): void
    {
        $this->tceAddNotice($message, 'info', $linkText, $linkUrl);
    }

    /**
     * Create a warning notice.
     *
     * @param string $message
     * @param string $linkText
     * @param string $linkUrl
     */
    public function tceAddWarning(string $message, string $linkText = '', string $linkUrl = ''): void
    {
        $this->tceAddNotice($message, 'warning', $linkText, $linkUrl);
    }

    /**
     * Show the created TCE notices.
     *
     * @param string $setting # Optional. Slug title of a specific setting whose errors you want. Default 'tce-sharing-notices'.
     */
    public function tceShowNotices(string $setting = ''): void
    {
        $setting = $setting ?: $this->settingIdentifier;

        $this->wpNoticesWrapper->tceShowNotices($setting);
    }

    /**
     * Clear all notices
     */
    public function tceClearAllNotices(): void
    {
        foreach ([
            'user_admin_notices',
            'network_admin_notices',
            'admin_notices',
            'all_admin_notices'
        ] as $tag) {
            $this->tceClearNotices($tag);
        }
    }

    /**
     * @param string $message # The formatted message text to display to the user.
     * @param string $type # Message type, controls HTML class. Possible values include 'error', 'success', 'warning', 'info'.
     * @param string $linkText
     * @param string $linkUrl
     */
    private function tceAddNotice(string $message, string $type, string $linkText, string $linkUrl): void
    {
        if ($linkText && $linkUrl) {
            $message .= ' <a href="' . $linkUrl . '">' . $linkText . '</a>';
        }

        $name = $this->name . '-' . $type;

        $this->wpNoticesWrapper->tceAddNotice($message, $name, $type, $this->settingIdentifier);
    }


    /**
     * Settings errors are stored in a transient for 30 seconds so that this transient can be retrieved on the next page load.
     */
    public function tceSetTransient(): void
    {
        $this->wpNoticesWrapper->tceSetTransient();
    }

    /**
     * @param string $tag
     */
    private function tceClearNotices(string $tag): void
    {
        $this->wpNoticesWrapper->tceClearNotices($tag, $this->tceAdminNoticesPriorities);
    }
}

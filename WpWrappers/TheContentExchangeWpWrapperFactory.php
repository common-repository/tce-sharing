<?php

namespace TheContentExchange\WpWrappers;

use TheContentExchange\WpWrappers\WpData\TheContentExchangeWpAttachmentWrapper;
use TheContentExchange\WpWrappers\WpData\TheContentExchangeWpOptionsWrapper;
use TheContentExchange\WpWrappers\WpData\TheContentExchangeWpPostWrapper;
use TheContentExchange\WpWrappers\WpData\TheContentExchangeWpUserWrapper;
use TheContentExchange\WpWrappers\WpFiles\TheContentExchangeWpFilePathWrapper;
use TheContentExchange\WpWrappers\WpFiles\TheContentExchangeWpFileUrlWrapper;
use TheContentExchange\WpWrappers\WpFilters\TheContentExchangeWpFilterWrapper;
use TheContentExchange\WpWrappers\WpFilters\TheContentExchangeWpSanitizeWrapper;
use TheContentExchange\WpWrappers\WpHttp\TheContentExchangeWpRedirectWrapper;
use TheContentExchange\WpWrappers\WpHttp\TheContentExchangeWpRestRouteWrapper;
use TheContentExchange\WpWrappers\WpHttp\TheContentExchangeWpSafeRemoteRequestWrapper;
use TheContentExchange\WpWrappers\WpNotices\TheContentExchangeWpNoticesWrapper;
use TheContentExchange\WpWrappers\WpViews\TheContentExchangeWpGutenbergEditorWrapper;
use TheContentExchange\WpWrappers\WpViews\TheContentExchangeWpPageWrapper;

/**
 * Class TheContentExchangeWpWrapperFactory
 * @package TheContentExchange\WpWrappers
 */
class TheContentExchangeWpWrapperFactory
{
    public function tceCreateWpOptionsWrapper(): TheContentExchangeWpOptionsWrapper
    {
        return new TheContentExchangeWpOptionsWrapper();
    }

    public function tceCreateWpFilePathWrapper(): TheContentExchangeWpFilePathWrapper
    {
        return new TheContentExchangeWpFilePathWrapper();
    }

    public function tceCreateWpFileUrlWrapper(): TheContentExchangeWpFileUrlWrapper
    {
        return new TheContentExchangeWpFileUrlWrapper();
    }

    public function tceCreateWpRedirectWrapper(): TheContentExchangeWpRedirectWrapper
    {
        return new TheContentExchangeWpRedirectWrapper();
    }

    public function tceCreateWpRestRouteWrapper(): TheContentExchangeWpRestRouteWrapper
    {
        return new TheContentExchangeWpRestRouteWrapper();
    }

    public function tceCreateWpSafeRemoteRequestWrapper(): TheContentExchangeWpSafeRemoteRequestWrapper
    {
        return new TheContentExchangeWpSafeRemoteRequestWrapper();
    }

    public function tceCreateWpPageWrapper(): TheContentExchangeWpPageWrapper
    {
        return new TheContentExchangeWpPageWrapper();
    }

    public function tceCreateWpPostWrapper(): TheContentExchangeWpPostWrapper
    {
        return new TheContentExchangeWpPostWrapper();
    }

    public function tceCreateWpUserWrapper(): TheContentExchangeWpUserWrapper
    {
        return new TheContentExchangeWpUserWrapper();
    }

    public function tceCreateWpGutenbergEditorWrapper(): TheContentExchangeWpGutenbergEditorWrapper
    {
        return new TheContentExchangeWpGutenbergEditorWrapper();
    }

    public function tceCreateWpNoticesWrapper(): TheContentExchangeWpNoticesWrapper
    {
        return new TheContentExchangeWpNoticesWrapper();
    }

    public function tceCreateWpFilterWrapper(): TheContentExchangeWpFilterWrapper
    {
        return new TheContentExchangeWpFilterWrapper();
    }

    public function tceCreateWpSanitizeWrapper(): TheContentExchangeWpSanitizeWrapper
    {
        return new TheContentExchangeWpSanitizeWrapper();
    }

    public function tceCreateWpAttachmentWrapper(): TheContentExchangeWpAttachmentWrapper
    {
        return new TheContentExchangeWpAttachmentWrapper();
    }
}

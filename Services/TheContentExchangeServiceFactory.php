<?php


namespace TheContentExchange\Services;

use TheContentExchange\Services\Filters\TheContentExchangeInputFilterService;
use TheContentExchange\Services\Filters\TheContentExchangeSanitizeOptionService;
use TheContentExchange\Services\Notices\TheContentExchangeAuthenticationNoticesService;
use TheContentExchange\Services\Notices\TheContentExchangeConfigurationNoticesService;
use TheContentExchange\Services\Notices\TheContentExchangeNoticesService;
use TheContentExchange\Services\Notices\TheContentExchangeUploadNoticesService;
use TheContentExchange\Services\TCE\TheContentExchangeConfigurationService;
use TheContentExchange\Services\TCE\TheContentExchangePostService;
use TheContentExchange\Services\TCE\TheContentExchangeSessionService;
use TheContentExchange\Services\Upload\TheContentExchangeAutoUploadService;
use TheContentExchange\Services\Upload\TheContentExchangePostBulkUploadService;
use TheContentExchange\Services\Upload\TheContentExchangePostConversionService;
use TheContentExchange\Services\Upload\TheContentExchangePostUploadService;
use TheContentExchange\Services\WP\TheContentExchangeWpApiService;
use TheContentExchange\Services\WP\TheContentExchangeWpAttachmentService;
use TheContentExchange\Services\WP\TheContentExchangeWpGutenbergEditorService;
use TheContentExchange\Services\WP\TheContentExchangeWpHttpService;
use TheContentExchange\Services\WP\TheContentExchangeWpMediaCustomizationService;
use TheContentExchange\Services\WP\TheContentExchangeWpPathService;
use TheContentExchange\Services\WP\TheContentExchangeWpPostCustomizationService;
use TheContentExchange\Services\WP\TheContentExchangeWpPostContentParsingService;
use TheContentExchange\Services\WP\TheContentExchangeWpPostService;
use TheContentExchange\Services\WP\TheContentExchangeWpUrlService;
use TheContentExchange\Services\WP\TheContentExchangeWpUserService;
use TheContentExchange\Services\WP\TheContentExchangeWpViewService;
use TheContentExchange\WpWrappers\TheContentExchangeWpWrapperFactory;

/**
 * Class TheContentExchangeServiceFactory
 * @package TheContentExchange\Services
 */
class TheContentExchangeServiceFactory
{
    /**
     * @var TheContentExchangeWpWrapperFactory
     */
    private $wpWrapperFactory;

    /**
     * TheContentExchangeServiceFactory constructor.
     */
    public function __construct()
    {
        $this->wpWrapperFactory = new TheContentExchangeWpWrapperFactory();
    }

    public function tceCreateConfigurationService(): TheContentExchangeConfigurationService
    {
        return new TheContentExchangeConfigurationService($this->wpWrapperFactory);
    }

    public function tceCreateSessionService(): TheContentExchangeSessionService
    {
        return new TheContentExchangeSessionService($this->wpWrapperFactory);
    }

    public function tceCreateWpViewService(): TheContentExchangeWpViewService
    {
        return new TheContentExchangeWpViewService($this->wpWrapperFactory);
    }

    public function tceCreateWpUrlService(): TheContentExchangeWpUrlService
    {
        return new TheContentExchangeWpUrlService($this->wpWrapperFactory);
    }

    public function tceCreateWpPathService(): TheContentExchangeWpPathService
    {
        return new TheContentExchangeWpPathService($this->wpWrapperFactory);
    }

    public function tceCreateWpApiService(): TheContentExchangeWpApiService
    {
        return new TheContentExchangeWpApiService($this->wpWrapperFactory);
    }

    public function tceCreateWpHttpService(): TheContentExchangeWpHttpService
    {
        return new TheContentExchangeWpHttpService($this->wpWrapperFactory);
    }

    public function tceCreatePostUploadService(): TheContentExchangePostUploadService
    {
        return new TheContentExchangePostUploadService(
            $this->tceCreateSessionService(),
            $this->tceCreateWpHttpService(),
            $this->tceCreatePostConversionService(),
            $this->tceCreateWpPostService()
        );
    }

    public function tceCreatePostConversionService(): TheContentExchangePostConversionService
    {
        return new TheContentExchangePostConversionService(
            $this->wpWrapperFactory,
            $this->tceCreateConfigurationService(),
            $this->tceCreateWpPostContentParsingService(),
            $this->tceCreateWpPostCustomizationService(),
            $this->tceCreateWpPostService(),
            $this->tceCreateWpAttachmentService()
        );
    }

    public function tceCreatePostBulkUploadService(): TheContentExchangePostBulkUploadService
    {
        return new TheContentExchangePostBulkUploadService(
            $this->wpWrapperFactory,
            $this->tceCreatePostUploadService(),
            $this->tceCreateWpUrlService(),
            $this->tceCreateWpViewService(),
            $this->tceCreateUploadNoticesService(),
            $this->tceCreateInputFilterService()
        );
    }

    public function tceCreateWpPostCustomizationService(): TheContentExchangeWpPostCustomizationService
    {
        return new TheContentExchangeWpPostCustomizationService($this->tceCreateWpPostService());
    }

    public function tceCreateWpMediaCustomizationService(): TheContentExchangeWpMediaCustomizationService
    {
        return new TheContentExchangeWpMediaCustomizationService(
            $this->wpWrapperFactory,
            $this->tceCreateConfigurationService(),
            $this->tceCreateWpUrlService(),
            $this->tceCreateNoticesServices(),
            $this->tceCreateWpViewService(),
            $this->tceCreateWpAttachmentService(),
            $this->tceCreateInputFilterService()
        );
    }

    public function tceCreateWpPostContentParsingService(): TheContentExchangeWpPostContentParsingService
    {
        return new TheContentExchangeWpPostContentParsingService();
    }

    public function tceCreateAutoUploadService(): TheContentExchangeAutoUploadService
    {
        return new TheContentExchangeAutoUploadService(
            $this->tceCreatePostUploadService(),
            $this->tceCreateConfigurationService(),
            $this->tceCreatePostService()
        );
    }

    public function tceCreateWpGutenbergEditorService(): TheContentExchangeWpGutenbergEditorService
    {
        return new TheContentExchangeWpGutenbergEditorService($this->wpWrapperFactory);
    }

    public function tceCreateWpUserService(): TheContentExchangeWpUserService
    {
        return new TheContentExchangeWpUserService($this->wpWrapperFactory);
    }
    
    public function tceCreatePostService(): TheContentExchangePostService
    {
        return new TheContentExchangePostService($this->wpWrapperFactory);
    }

    public function tceCreateNoticesServices(): TheContentExchangeNoticesService
    {
        return new TheContentExchangeNoticesService($this->wpWrapperFactory);
    }

    public function tceCreateAuthenticationNoticesService(): TheContentExchangeAuthenticationNoticesService
    {
        return new TheContentExchangeAuthenticationNoticesService($this->wpWrapperFactory);
    }

    public function tceCreateConfigurationNoticesService(): TheContentExchangeConfigurationNoticesService
    {
        return new TheContentExchangeConfigurationNoticesService($this->wpWrapperFactory);
    }

    public function tceCreateUploadNoticesService(): TheContentExchangeUploadNoticesService
    {
        return new TheContentExchangeUploadNoticesService($this->wpWrapperFactory);
    }

    public function tceCreateWpPostService(): TheContentExchangeWpPostService
    {
        return new TheContentExchangeWpPostService($this->wpWrapperFactory);
    }

    public function tceCreateInputFilterService(): TheContentExchangeInputFilterService
    {
        return new TheContentExchangeInputFilterService($this->wpWrapperFactory);
    }

    public function tceCreateSanitizeOptionService(): TheContentExchangeSanitizeOptionService
    {
        return new TheContentExchangeSanitizeOptionService(
            $this->wpWrapperFactory,
            $this->tceCreateInputFilterService(),
            $this->tceCreateConfigurationService()
        );
    }

    public function tceCreateWpAttachmentService(): TheContentExchangeWpAttachmentService
    {
        return new TheContentExchangeWpAttachmentService(
            $this->wpWrapperFactory,
            $this->tceCreateConfigurationService()
        );
    }
}

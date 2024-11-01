<?php


namespace TheContentExchange\Views\Components\BulkAttachmentCopyrightComponent;

use TheContentExchange\Services\TheContentExchangeServiceFactory;
use TheContentExchange\Services\TCE\TheContentExchangeConfigurationService;
use TheContentExchange\Services\WP\TheContentExchangeWpPathService;
use TheContentExchange\Services\WP\TheContentExchangeWpUrlService;
use TheContentExchange\Services\WP\TheContentExchangeWpViewService;

/**
 * Class TheContentExchangeBulkAttachmentCopyrightComponent
 * @package TheContentExchange\Views\Components\BulkAttachmentCopyrightComponent
 */
class TheContentExchangeBulkAttachmentCopyrightComponent
{
    /**
     * @var TheContentExchangeServiceFactory
     */
    private $serviceFactory;

    /**
     * @var TheContentExchangeWpViewService
     */
    private $wpViewService;

    /**
     * @var TheContentExchangeWpUrlService
     */
    private $wpUrlService;

    /**
     * @var TheContentExchangeWpPathService
     */
    private $wpPathService;

    /**
     * @var TheContentExchangeConfigurationService
     */
    private $tceConfigurationService;

    /**
     * @var string
     */
    private $componentName = 'Components/BulkAttachmentCopyrightComponent';

    /**
     * TheContentExchangeBulkAttachmentCopyrightComponent constructor.
     * @param TheContentExchangeServiceFactory $serviceFactory
     */
    public function __construct(TheContentExchangeServiceFactory $serviceFactory)
    {
        $this->serviceFactory = $serviceFactory;
        $this->wpViewService = $this->serviceFactory->tceCreateWpViewService();
        $this->wpUrlService = $this->serviceFactory->tceCreateWpUrlService();
        $this->wpPathService = $this->serviceFactory->tceCreateWpPathService();
        $this->tceConfigurationService = $this->serviceFactory->tceCreateConfigurationService();
    }

    public function tceEnqueueStyles(): void
    {
        if ($this->wpViewService->tceCheckCurrentPage('upload')) {
            $this->wpViewService->tceEnqueueStyleSheet(
                'tce-sharing-bulk-attachment-copyright-button',
                $this->wpUrlService->tceGetCssFileUrl($this->componentName, "bulk-attachment-copyright-button")
            );
        }
    }

    public function tceEnqueueScripts(): void
    {
        if ($this->wpViewService->tceCheckCurrentPage('upload') || $this->wpViewService->tceCheckCurrentPage('attachment')) {
            $this->wpViewService->tceEnqueueScript(
                'tce-sharing-bulk-attachment-copyright-ajax-handler',
                $this->wpUrlService->tceGetJsFileUrl($this->componentName, "bulk-ajax-handler"),
                ['jquery']
            );

            $bulkParams = [
                'copyrightUsageValue' => $this->tceConfigurationService->tceGetCopyrightUsage(),
                'copyrightInformationValue' => $this->tceConfigurationService->tceGetDefaultAttachmentCopyright(),
            ];

            $this->wpViewService->tceLocalize('tce-sharing-bulk-attachment-copyright-ajax-handler', 'bulkParams', $bulkParams);
        }
    }
}

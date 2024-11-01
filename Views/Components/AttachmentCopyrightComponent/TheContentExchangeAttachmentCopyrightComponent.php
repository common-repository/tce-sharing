<?php


namespace TheContentExchange\Views\Components\AttachmentCopyrightComponent;

use TheContentExchange\Services\TCE\TheContentExchangeConfigurationService;
use TheContentExchange\Services\TheContentExchangeServiceFactory;
use TheContentExchange\Services\WP\TheContentExchangeWpMediaCustomizationService;
use TheContentExchange\Services\WP\TheContentExchangeWpPathService;
use TheContentExchange\Services\WP\TheContentExchangeWpUrlService;
use TheContentExchange\Services\WP\TheContentExchangeWpViewService;

/**
 * Class TheContentExchangeAttachmentCopyrightComponent
 * @package TheContentExchange\Views\Components\AttachmentCopyrightComponent
 */
class TheContentExchangeAttachmentCopyrightComponent
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
     * @var TheContentExchangeWpMediaCustomizationService
     */
    private $wpMediaCustomizationService;

    /**
     * @var TheContentExchangeConfigurationService
     */
    private $tceConfigurationService;

    /**
     * @var string
     */
    private $componentName = 'Components/AttachmentCopyrightComponent';

    /**
     * AttachmentCopyrightComponent constructor.
     * @param TheContentExchangeServiceFactory $serviceFactory
     */
    public function __construct(TheContentExchangeServiceFactory $serviceFactory)
    {
        $this->serviceFactory = $serviceFactory;
        $this->wpViewService = $this->serviceFactory->tceCreateWpViewService();
        $this->wpUrlService = $this->serviceFactory->tceCreateWpUrlService();
        $this->wpPathService = $this->serviceFactory->tceCreateWpPathService();
        $this->wpMediaCustomizationService = $this->serviceFactory->tceCreateWpMediaCustomizationService();
        $this->tceConfigurationService = $this->serviceFactory->tceCreateConfigurationService();
    }

    public function tceEnqueueStyles(): void
    {
        $this->wpViewService->tceEnqueueStyleSheet(
            'tce-sharing-attachment-copyright-form',
            $this->wpUrlService->tceGetCssFileUrl($this->componentName, "attachment-copyright-form")
        );
    }

    public function tceEnqueueScripts(): void
    {
        if ($this->wpViewService->tceCheckCurrentPage('upload') || $this->wpViewService->tceCheckCurrentPage('attachment')) {
            $this->wpViewService->tceEnqueueScript(
                'tce-sharing-attachment-copyright-ajax-handler',
                $this->wpUrlService->tceGetJsFileUrl($this->componentName, "ajax-handler"),
                ['jquery']
            );

            $params = [
                'organisationName' => $this->tceConfigurationService->tceGetOrganisationName(),
            ];

            $this->wpViewService->tceLocalize('tce-sharing-attachment-copyright-ajax-handler', 'params', $params);
        }
    }
}

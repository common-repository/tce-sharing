<?php


namespace TheContentExchange\Views\Components\GutenbergTceSharingComponent;

use TheContentExchange\Services\TheContentExchangeServiceFactory;
use TheContentExchange\Services\TCE\TheContentExchangeConfigurationService;
use TheContentExchange\Services\TCE\TheContentExchangePostService;
use TheContentExchange\Services\WP\TheContentExchangeWpGutenbergEditorService;
use TheContentExchange\Services\WP\TheContentExchangeWpPathService;
use TheContentExchange\Services\WP\TheContentExchangeWpUrlService;
use TheContentExchange\Services\WP\TheContentExchangeWpViewService;

/**
 * Class TheContentExchangeGutenbergComponent
 * @package TheContentExchange\Views\Components\GutenbergTceSharingComponent
 */
class TheContentExchangeGutenbergComponent
{
    /**
     * @var TheContentExchangeServiceFactory
     */
    private $serviceFactory;

    /**
     * @var TheContentExchangeWpGutenbergEditorService
     */
    private $wpGutenbergEditorService;

    /**
     * @var TheContentExchangeWpViewService
     */
    private $wpViewService;

    /**
     * @var TheContentExchangeWpPathService
     */
    private $wpPathService;

    /**
     * @var TheContentExchangeWpUrlService
     */
    private $wpUrlService;

    /**
     * @var TheContentExchangeConfigurationService
     */
    private $tceConfigurationService;

    /**
     * @var TheContentExchangePostService
     */
    private $tcePostService;

    /**
     * @var string
     */
    private $componentName = 'Components/GutenbergTceSharingComponent';

    /**
     * TheContentExchangeGutenbergComponent constructor.
     *
     * @param TheContentExchangeServiceFactory $serviceFactory
     */
    public function __construct(TheContentExchangeServiceFactory $serviceFactory)
    {
        $this->serviceFactory = $serviceFactory;
        $this->wpGutenbergEditorService = $this->serviceFactory->tceCreateWpGutenbergEditorService();
        $this->wpViewService = $this->serviceFactory->tceCreateWpViewService();
        $this->wpPathService = $this->serviceFactory->tceCreateWpPathService();
        $this->wpUrlService = $this->serviceFactory->tceCreateWpUrlService();
        $this->tceConfigurationService = $this->serviceFactory->tceCreateConfigurationService();
        $this->tcePostService = $this->serviceFactory->tceCreatePostService();
    }

    public function tceRegisterComponent(): void
    {
        $this->wpGutenbergEditorService->tceAddSideMetaBox(
            'tce-sharing-auto-upload-post',
            'Upload To TCE',
            [$this, 'tceRenderComponent']
        );
    }

    public function tceEnqueueStyles(): void
    {
        if ($this->wpViewService->tceCheckCurrentPage('post')) {
            $this->wpViewService->tceEnqueueStyleSheet(
                'tce-sharing-auto-upload-post-form',
                $this->wpUrlService->tceGetCssFileUrl($this->componentName, "auto-upload-post-form")
            );
        }
    }

    public function tceEnqueueScripts(): void
    {
        if ($this->wpViewService->tceCheckCurrentPage('post')) {
            $this->wpViewService->tceEnqueueScript(
                'auto-upload-post-ajax-handler',
                $this->wpUrlService->tceGetJsFileUrl($this->componentName, "ajax-handler"),
                ['jquery']
            );

            $params = [
                'wpAjaxUrl' => $this->wpUrlService->tceGetWpAdminAjaxUrl(),
                'postId' => $this->wpViewService->tceGetCurrentPageId()
            ];

            $this->wpViewService->tceLocalize('auto-upload-post-ajax-handler', 'params', $params);
        }
    }

    public function tceRenderComponent(): void
    {
        $autoUploadDefault = $this->tceConfigurationService->tceGetAutoUploadDefault();
        $autoUploadPost = $this->tcePostService->tceGetAutoUploadPost($this->wpViewService->tceGetCurrentPageId());
        require $this->wpPathService->tceGetPartialPath($this->componentName, 'auto-upload-post-form');
    }
}

<?php


namespace TheContentExchange\Core;

use TheContentExchange\Controllers\TheContentExchangeConfigurationController;
use TheContentExchange\Controllers\TheContentExchangePostController;
use TheContentExchange\Controllers\TheContentExchangeSessionController;
use TheContentExchange\Services\TheContentExchangeServiceFactory;
use TheContentExchange\Views\Components\AttachmentCopyrightComponent\TheContentExchangeAttachmentCopyrightComponent;
use TheContentExchange\Views\Components\BulkAttachmentCopyrightComponent\TheContentExchangeBulkAttachmentCopyrightComponent;
use TheContentExchange\Views\Components\GutenbergTceSharingComponent\TheContentExchangeGutenbergComponent;
use TheContentExchange\Views\TceSharingConfiguration\TheContentExchangeConfiguration;
use TheContentExchange\Views\TceSharingMain\TheContentExchangeMain;

/**
 * Class TheContentExchangeSharing
 * @package TheContentExchange\Core
 */

/**
 * The core plugin class.
 * This class is used to define controllers, Views and bulk actions for the admin-side of the WordPress website.
 */

class TheContentExchangeSharing
{
    /**
     * The loader that's responsible for maintaining and registering all hooks that power the plugin.
     *
     * @var TheContentExchangeSharingLoader $loader - Maintains and registers all hooks for the plugin.
     */
    private $loader;

    /**
     * @var TheContentExchangeServiceFactory
     */
    private $serviceFactory;

    /**
     * TheContentExchangeSharing constructor.
     * Instantiates the loader, initializes the controllers, Views and bulk actions.
     */
    public function __construct()
    {
        $this->loader = new TheContentExchangeSharingLoader();
        $this->serviceFactory = new TheContentExchangeServiceFactory();

        $this->tceClearAllNotices();
        $this->tceDefineControllers();
        $this->tceDefineViews();
        $this->tceDefineBulkActions();
        $this->tceDefineCustomPostColumns();
        $this->tceDefinePostCreationOrUpdateActions();
        $this->tceDefineGutenbergEditorComponents();
        $this->tceDefineMediaCustomFields();
    }

    /**
     * Registers the plugin Views to WordPress
     */
    private function tceDefineViews(): void
    {
        // Define the main page
        $tceSharingMain = new TheContentExchangeMain($this->serviceFactory);

        $this->loader->tceAddAction('admin_menu', $tceSharingMain, 'tceRegisterPage');
        $this->loader->tceAddAction('admin_enqueue_scripts', $tceSharingMain, 'tceEnqueueScripts');
        $this->loader->tceAddAction('admin_enqueue_scripts', $tceSharingMain, 'tceEnqueueStyles');
        $this->loader->tceAddAdminNotice($tceSharingMain, 'tceNotifyAuthenticationStatus');
        $this->loader->tceAddAdminNotice($tceSharingMain, 'tceNotifyConfigurationUpdate');

        // Define configuration page
        $tceSharingConfiguration = new TheContentExchangeConfiguration($this->serviceFactory);

        $this->loader->tceAddAction('admin_menu', $tceSharingConfiguration, 'tceRegisterPage');
        $this->loader->tceAddAction('admin_enqueue_scripts', $tceSharingConfiguration, 'tceEnqueueScripts');
        $this->loader->tceAddAction('admin_enqueue_scripts', $tceSharingConfiguration, 'tceEnqueueStyles');
        $this->loader->tceAddAdminNotice($tceSharingConfiguration, 'tceNotifyConfigurationError');
    }

    /**
     * Clear all notices, except the once defined for TCE.
     */
    private function tceClearAllNotices(): void
    {
        $tceNoticesService = $this->serviceFactory->tceCreateNoticesServices();
        $this->loader->tceAddAction('in_admin_header', $tceNoticesService, 'tceClearAllNotices', 999);
    }

    /**
     * Registers the plugin controllers to WordPress.
     */
    private function tceDefineControllers(): void
    {
        // Add configuration controller
        $tceConfigurationController = new TheContentExchangeConfigurationController($this->serviceFactory);
        $tcePostController = new TheContentExchangePostController($this->serviceFactory);

        $this->loader->tceAddAction(
            'admin_post_tce_sharing_store_configuration',
            $tceConfigurationController,
            'tceStore'
        );

        $this->loader->tceAddAction(
            'wp_ajax_tce_sharing_store_configuration',
            $tceConfigurationController,
            'tceStore'
        );

        $this->loader->tceAddAction(
            'wp_ajax_tce_sharing_store_auto_upload_post',
            $tcePostController,
            'tceUpdateAutoUploadPost'
        );

        // Add session controller
        $tceSessionController = new TheContentExchangeSessionController($this->serviceFactory);
        $this->loader->tceAddAction('rest_api_init', $tceSessionController, 'tceRegisterRoutes');

        $this->loader->tceAddAction(
            'admin_post_tce_sharing_disconnect',
            $tceSessionController,
            'tceRemoveSessionDetails'
        );
    }

    /**
     * Registers the custom bulk actions to WordPress.
     */
    private function tceDefineBulkActions(): void
    {
        $postBulkUploadService = $this->serviceFactory->tceCreatePostBulkUploadService();

        $this->loader->tceAddFilter('bulk_actions-edit-post', $postBulkUploadService, 'tceRegisterBulkUpload');
        $this->loader->tceAddFilter('handle_bulk_actions-edit-post', $postBulkUploadService, 'tceHandleBulkUpload', 10, 3);
        $this->loader->tceAddAdminNotice($postBulkUploadService, 'tceNotifyUploadStatus');

        $wpMediaCustomizationService = $this->serviceFactory->tceCreateWpMediaCustomizationService();
        $this->loader->tceAddFilter('bulk_actions-upload', $wpMediaCustomizationService, 'tceRegisterBulkCopyright');
        $this->loader->tceAddFilter('handle_bulk_actions-upload', $wpMediaCustomizationService, 'tceHandleBulkCopyright', 10, 3);
        $this->loader->tceAddAdminNotice($wpMediaCustomizationService, 'tceNotifyEditAttachmentCopyrightStatus');

        $bulkAttachmentCopyrightComponent = new TheContentExchangeBulkAttachmentCopyrightComponent($this->serviceFactory);
        $this->loader->tceAddAction('admin_enqueue_scripts', $bulkAttachmentCopyrightComponent, 'tceEnqueueStyles');
        $this->loader->tceAddAction('admin_enqueue_scripts', $bulkAttachmentCopyrightComponent, 'tceEnqueueScripts');
    }

    /**
     * Registers the custom TCE column on the Posts overview page.
     */
    private function tceDefineCustomPostColumns(): void
    {
        $wpPostCustomizationService = $this->serviceFactory->tceCreateWpPostCustomizationService();

        $this->loader->tceAddFilter('manage_post_posts_columns', $wpPostCustomizationService, 'tceRegisterSharedWithTceColumn');
        $this->loader->tceAddAction('manage_post_posts_custom_column', $wpPostCustomizationService, 'tceRenderSharedWithTceColumn', 10, 2);
        $this->loader->tceAddAction('admin_enqueue_scripts', $wpPostCustomizationService, 'tceStyleSharedWithTceColumn');
    }

    /**
     * Registers custom functions when a post is saved.
     */
    private function tceDefinePostCreationOrUpdateActions(): void
    {
        $autoUploadService = $this->serviceFactory->tceCreateAutoUploadService();
        $this->loader->tceAddAction('save_post', $autoUploadService, 'tceUploadPostOnCreationOrUpdate', 99999, 2);

        $tcePostController = new TheContentExchangePostController($this->serviceFactory);
        $this->loader->tceAddAction('save_post', $tcePostController, 'tceUpdateAutoUploadPost');
    }

    /**
     * Registers custom components for the Gutenberg Editor.
     */
    private function tceDefineGutenbergEditorComponents(): void
    {
        $gutenbergTceSharingComponent = new TheContentExchangeGutenbergComponent($this->serviceFactory);

        $this->loader->tceAddAction('add_meta_boxes', $gutenbergTceSharingComponent, 'tceRegisterComponent');
        $this->loader->tceAddAction('admin_enqueue_scripts', $gutenbergTceSharingComponent, 'tceEnqueueStyles');
        $this->loader->tceAddAction('admin_enqueue_scripts', $gutenbergTceSharingComponent, 'tceEnqueueScripts');
    }

    private function tceDefineMediaCustomFields(): void
    {
        $wpMediaCustomizationService = $this->serviceFactory->tceCreateWpMediaCustomizationService();
        $this->loader->tceAddFilter('attachment_fields_to_edit', $wpMediaCustomizationService, 'tceRegisterCopyrightUsageRadioButtons', 10, 2);
        $this->loader->tceAddFilter('attachment_fields_to_save', $wpMediaCustomizationService, 'tceSaveCopyrightUsageRadioButtons', 10, 2);
        $this->loader->tceAddFilter('attachment_fields_to_edit', $wpMediaCustomizationService, 'tceRegisterCopyrightInput', 10, 2);
        $this->loader->tceAddFilter('attachment_fields_to_save', $wpMediaCustomizationService, 'tceSaveCopyrightInput', 10, 2);
        $this->loader->tceAddAction('add_attachment', $wpMediaCustomizationService, 'tceAddAttachmentCopyrightDefaultValues', 10, 1);

        $attachmentCopyrightComponent = new TheContentExchangeAttachmentCopyrightComponent($this->serviceFactory);
        $this->loader->tceAddAction('admin_enqueue_scripts', $attachmentCopyrightComponent, 'tceEnqueueStyles');
        $this->loader->tceAddAction('admin_enqueue_scripts', $attachmentCopyrightComponent, 'tceEnqueueScripts');
    }

    /**
     * Makes the loader register all actions and filters to WordPress
     */
    public function tceRun(): void
    {
        $this->loader->tceRunLoader();
    }
}

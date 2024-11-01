<?php


namespace TheContentExchange\Services\Upload;

use Exception;
use TheContentExchange\Exceptions\TheContentExchangeConnectionException;
use TheContentExchange\Exceptions\TheContentExchangeConfigurationException;
use TheContentExchange\Exceptions\TheContentExchangeUploadFailedException;
use TheContentExchange\Exceptions\TheContentExchangeWordPressPostIncompleteException;
use TheContentExchange\Exceptions\TheContentExchangeCopyrightUsageException;
use TheContentExchange\Exceptions\TheContentExchangeWordPressPostUnpublishedException;
use TheContentExchange\Services\Filters\TheContentExchangeInputFilterService;
use TheContentExchange\Services\Notices\TheContentExchangeUploadNoticesService;
use TheContentExchange\Services\WP\TheContentExchangeWpUrlService;
use TheContentExchange\Services\WP\TheContentExchangeWpViewService;
use TheContentExchange\WpWrappers\WpData\TheContentExchangeWpPostWrapper;
use TheContentExchange\WpWrappers\WpData\TheContentExchangeWpUserWrapper;
use TheContentExchange\WpWrappers\TheContentExchangeWpWrapperFactory;

/**
 * Class TheContentExchangePostBulkUploadService
 * @package TheContentExchange\Services\Upload
 */
class TheContentExchangePostBulkUploadService
{
    /**
     * @var TheContentExchangeWpWrapperFactory
     */
    private $wpWrapperFactory;

    /**
     * @var TheContentExchangeWpPostWrapper
     */
    private $wpPostWrapper;

    /**
     * @var TheContentExchangeWpUserWrapper
     */
    private $wpUserWrapper;

    /**
     * @var TheContentExchangePostUploadService
     */
    private $postUploadService;

    /**
     * @var TheContentExchangeWpUrlService
     */
    private $wpUrlService;

    /**
     * @var TheContentExchangeWpViewService
     */
    private $wpViewService;

    /**
     * @var TheContentExchangeUploadNoticesService
     */
    private $tceUploadNoticesService;

    /**
     * @var TheContentExchangeInputFilterService
     */
    private $tceInputFilterService;

    /**
     * TheContentExchangePostBulkUploadService constructor.
     *
     * @param TheContentExchangeWpWrapperFactory $wpWrapperFactory
     * @param TheContentExchangePostUploadService $postUploadService
     * @param TheContentExchangeWpUrlService $wpUrlService
     * @param TheContentExchangeWpViewService $wpViewService
     * @param TheContentExchangeUploadNoticesService $tceUploadNoticesService
     * @param TheContentExchangeInputFilterService $tceInputFilterService
     */
    public function __construct(
        TheContentExchangeWpWrapperFactory $wpWrapperFactory,
        TheContentExchangePostUploadService $postUploadService,
        TheContentExchangeWpUrlService $wpUrlService,
        TheContentExchangeWpViewService $wpViewService,
        TheContentExchangeUploadNoticesService $tceUploadNoticesService,
        TheContentExchangeInputFilterService $tceInputFilterService
    ) {
        $this->wpWrapperFactory = $wpWrapperFactory;
        $this->wpPostWrapper = $this->wpWrapperFactory->tceCreateWpPostWrapper();
        $this->wpUserWrapper = $this->wpWrapperFactory->tceCreateWpUserWrapper();

        $this->postUploadService = $postUploadService;
        $this->wpUrlService = $wpUrlService;
        $this->wpViewService = $wpViewService;
        $this->tceUploadNoticesService = $tceUploadNoticesService;
        $this->tceInputFilterService = $tceInputFilterService;
    }


    /**
     * @param string[] $bulkActions
     * @return string[]
     */
    public function tceRegisterBulkUpload(array $bulkActions): array
    {
        if (!$this->wpUserWrapper->tceCheckIfUserHasRightTo('publish_posts')) {
            return $bulkActions;
        }

        $bulkActions['uploadToTce'] = 'Upload to TCE';

        return $bulkActions;
    }

    /**
     * @param string $redirectTo
     * @param mixed $doAction
     * @param int[] $postIds
     */
    public function tceHandleBulkUpload(string $redirectTo, $doAction, array $postIds): string
    {
        if ('uploadToTce' !== $doAction) {
            return $redirectTo;
        }

        $totalPostCount = count($postIds);
        $uploadedPostCount = 0;

        foreach ($postIds as $key => $id) {
            try {
                $this->postUploadService->tceUploadWpPost($id);
                $uploadedPostCount++;
            } catch (TheContentExchangeConfigurationException $e) {
                // @todo: Redirect and show notification that the plugin is not configured (Contact your admin)
            } catch (TheContentExchangeConnectionException $e) {
                // @todo: Redirect and show notification that the plugin is not connected (Contact your admin)
            } catch (TheContentExchangeUploadFailedException $e) {
                // @todo: Save notification in notification table
            } catch (TheContentExchangeWordPressPostIncompleteException $e) {
                // @todo: Save notification in notification table, provide detail what post info is lacking
            } catch (TheContentExchangeWordPressPostUnpublishedException $e) {
                // @todo: Save notification in notification table, tell user that post needs to be approved
            } catch (TheContentExchangeCopyrightUsageException $e) {
                $this->tceUploadNoticesService->tceAddError(
                    'TCE Sharing - One or multiple posts are not uploaded to TCE, because these have images 
                    attached that don\'t have copyright information. Go to the media library to add copyright information to
                    these images before uploading again.'
                );
            } catch (Exception $e) {
                // @todo: Save notification in notification table, tell user that post needs to be approved
            }
        }

        $redirectTo = $this->wpUrlService->tceAddQueryArgsToUrl([
            "total" => $totalPostCount,
            "success" => $uploadedPostCount,
            "settings-updated" => 'true'
        ], $redirectTo);

        $this->tceUploadNoticesService->tceSetTransient();

        wp_safe_redirect($redirectTo);
        exit;
    }

    public function tceNotifyUploadStatus(): void
    {
        $total = (int) $this->tceInputFilterService->tceFilterGetInput('total', FILTER_SANITIZE_NUMBER_INT);
        $success = (int) $this->tceInputFilterService->tceFilterGetInput('success', FILTER_SANITIZE_NUMBER_INT);

        if ($this->tcePageIsActive() && $total > 0) {
            if (0 === $success) {
                $this->tceUploadNoticesService->tceAddError(
                    'TCE Sharing - Bulk upload failed'
                );
            } elseif ($success < $total) {
                $this->tceUploadNoticesService->tceAddWarning(
                    'TCE Sharing - Bulk upload partially succeeded (' . $success . '/' . $total . ')'
                );
            } else {
                $this->tceUploadNoticesService->tceAddSuccess(
                    'TCE Sharing - Bulk upload successful'
                );
            }

            $this->tceUploadNoticesService->tceShowNotices();
        }
    }

    /**
     * Check whether current page is active.
     *
     * @return bool
     */
    private function tcePageIsActive(): bool
    {
        return $this->wpViewService->tceCheckCurrentPage("edit-post");
    }
}

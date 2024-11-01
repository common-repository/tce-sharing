<?php


namespace TheContentExchange\Services\Upload;

use TheContentExchange\Exceptions\TheContentExchangeConnectionException;
use TheContentExchange\Exceptions\TheContentExchangeConfigurationException;
use TheContentExchange\Exceptions\TheContentExchangeUploadFailedException;
use TheContentExchange\Exceptions\TheContentExchangeWordPressPostIncompleteException;
use TheContentExchange\Exceptions\TheContentExchangeWordPressPostUnpublishedException;
use TheContentExchange\Services\TCE\TheContentExchangeConfigurationService;
use TheContentExchange\Services\TCE\TheContentExchangePostService;

/**
 * Class TheContentExchangeAutoUploadService
 * @package TheContentExchange\Services\Upload
 */
class TheContentExchangeAutoUploadService
{
    /**
     * @var TheContentExchangePostUploadService
     */
    private $postUploadService;

    /**
     * @var TheContentExchangeConfigurationService
     */
    private $tceConfigurationService;

    /**
     * @var TheContentExchangePostService
     */
    private $tcePostService;

    /**
     * TheContentExchangeAutoUploadService constructor.
     *
     * @param TheContentExchangePostUploadService $postUploadService
     * @param TheContentExchangeConfigurationService $tceConfigurationService
     * @param TheContentExchangePostService $tcePostService
     */
    public function __construct(
        TheContentExchangePostUploadService $postUploadService,
        TheContentExchangeConfigurationService $tceConfigurationService,
        TheContentExchangePostService $tcePostService
    ) {
        $this->postUploadService = $postUploadService;
        $this->tceConfigurationService = $tceConfigurationService;
        $this->tcePostService = $tcePostService;
    }

    /**
     * @param int $postId
     * @param object $post
     * @return void
     */
    public function tceUploadPostOnCreationOrUpdate(int $postId, object $post): void
    {
        // Get the global auto upload setting.
        $autoUploadDefault = $this->tceConfigurationService->tceGetAutoUploadDefault();
        // Check if this is a new post.
        if ('auto-draft' === $post->post_status) {
            // Set the value based on the global setting.
            $this->tcePostService->tceSetAutoUploadPost($postId, $autoUploadDefault);
            // Quit, because a new post on creation does not have to be uploaded.
            return;
        }

        // Get the post specific auto upload setting.
        $autoUploadPost = $this->tcePostService->tceGetAutoUploadPost($postId);

        // Quit if the auto upload is disabled.
        if ('on' !== $autoUploadPost) {
            return;
        }
        try {
            // Upload the post to TCE
            $this->postUploadService->tceUploadWpPost($postId);
        } catch (TheContentExchangeConnectionException $e) {
            // @todo: store notification - not connected / tce-sharing error: contact site admin
        } catch (TheContentExchangeConfigurationException $e) {
            // @todo: store notification - not configured correctly / tce-sharing error: contact site admin
        } catch (TheContentExchangeUploadFailedException $e) {
            // @todo: store notification - upload unsuccessful / tce-sharing error: contact site admin
        } catch (TheContentExchangeWordPressPostIncompleteException $e) {
            // @todo: store notification - tell what post details are missing
        } catch (TheContentExchangeWordPressPostUnpublishedException $e) {
            // @todo: store notification - tell user posts cant be uploaded when not published yet
        } catch (\Exception $e) {
            // @todo: store notification - tell user posts cant be uploaded when not published yet
        }
    }
}

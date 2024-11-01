<?php


namespace TheContentExchange\Services\WP;

use Ramsey\Uuid\Uuid;
use TheContentExchange\WpWrappers\WpData\TheContentExchangeWpPostWrapper;
use TheContentExchange\WpWrappers\TheContentExchangeWpWrapperFactory;

/**
 * Class TheContentExchangeWpPostService
 * @package TheContentExchange\Services\WP
 */
class TheContentExchangeWpPostService
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
     * TheContentExchangeWpPostService constructor.
     *
     * @param TheContentExchangeWpWrapperFactory $wpWrapperFactory
     */
    public function __construct(TheContentExchangeWpWrapperFactory $wpWrapperFactory)
    {
        $this->wpWrapperFactory = $wpWrapperFactory;
        $this->wpPostWrapper = $this->wpWrapperFactory->tceCreateWpPostWrapper();
    }

    /**
     * This is for legacy articles. Their 'item_meta id' has been set the old way, but is not stored in the database yet.
     * Assuming no changes are made to the permalink after uploading to TCE,
     * we use the old function to create an 'item_meta id'.
     */
    public function tceUpdateSharedPostsItemMetaId(): void
    {
        foreach ($this->wpPostWrapper->tceGetUploadedPosts() as $post) {
            $isShared = $this->tceGetIsShared($post->ID);
            $id = $this->tceGetItemMetaId($post->ID);

            if ($isShared && '' === $id) {
                $this->tceSetItemMetaId($post->ID, $this->wpPostWrapper->tceGetRelativePostPermalink($post->ID));
            }
        }
    }

    /**
     * @param $postId
     */
    public function tceGetIsShared($postId): bool
    {
        return $this->wpPostWrapper->tceGetPostMeta($postId, 'sharedWithTce') === 'true';
    }

    /**
     * @param $postId
     * @param $value
     */
    public function tceSetIsShared($postId, $value): void
    {
        $this->wpPostWrapper->tceAddPostMeta($postId, 'sharedWithTce', $value);
    }

    /**
     * @param $postId
     */
    public function tceGetItemMetaId($postId): string
    {
        return $this->wpPostWrapper->tceGetPostMeta($postId, 'itemMetaId');
    }

    /**
     * @param $postId
     * @param $value
     */
    public function tceSetItemMetaId($postId, $value): void
    {
        $this->wpPostWrapper->tceAddPostMeta($postId, 'itemMetaId', $value);
    }

    /**
     * @param int $postId
     */
    public function tceCreateAndSetItemMetaId(int $postId): string
    {
        $id = $this->tceCreateItemMetaId($postId);
        $this->tceSetItemMetaId($postId, $id);

        return $id;
    }

    /**
     * Articles are identified by their unique 'item_meta id'.
     * @param int $postId
     */
    private function tceCreateItemMetaId(int $postId): string
    {
        $isShared = $this->tceGetIsShared($postId);
        $id = $this->tceGetItemMetaId($postId);

        if (!$isShared && '' === $id) {
            // The article is new (or not uploaded to TCE), create a unique 'item_meta id'.
            $id = Uuid::uuid4()->toString() . '-' . $postId;
        }

        return $id;
    }
}

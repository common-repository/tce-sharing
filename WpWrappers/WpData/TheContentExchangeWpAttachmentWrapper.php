<?php

namespace TheContentExchange\WpWrappers\WpData;

use InvalidArgumentException;

/**
 * Class TheContentExchangeWpAttachmentWrapper
 * @package TheContentExchange\WpWrappers\WpData
 */
class TheContentExchangeWpAttachmentWrapper extends TheContentExchangeWpPostWrapper
{
    /**
     * @var string
     */
    private $copyrightUsageKey = 'tce_copyright_usage';

    /**
     * @var string
     */
    private $copyrightInformationKey = 'tce_copyright_information';

    /**
     * @param int $id
     */
    public function tceGetAttachmentCopyrightUsage(int $id): string
    {
        return $this->tceGetPostMeta($id, $this->copyrightUsageKey);
    }

    /**
     * @param int $id
     * @param string $value
     */
    public function tceSetAttachmentCopyrightUsageValue(int $id, string $value): void
    {
        $this->tceAddPostMeta($id, $this->copyrightUsageKey, $value);
    }

    /**
     * @param int $id
     */
    public function tceGetAttachmentCopyrightInformationValue(int $id): string
    {
        return $this->tceGetPostMeta($id, $this->copyrightInformationKey);
    }

    /**
     * @param int $id
     * @param string $value
     */
    public function tceSetAttachmentCopyrightInformationValue(int $id, string $value): void
    {
        $this->tceAddPostMeta($id, $this->copyrightInformationKey, $value);
    }

    /**
     * @param int $id
     */
    public function tceGetAttachmentUrl(int $id): string
    {
        $url = wp_get_attachment_url($id);
        if ($url) {
            return $url;
        }
        throw new InvalidArgumentException(sprintf('Attachment with ID %s not found', $id));
    }

    /**
     * @param int $id
     */
    public function tceGetAttachmentCaption(int $id): string
    {
        return wp_get_attachment_caption($id);
    }

    /**
     * @param string $url
     */
    public function tceGetAttachmentIdByUrl(string $url): int
    {
        $attachmentId = 0;
        $dir = wp_upload_dir();

        // Check if url is in upload directory?
        if (false !== strpos($url, $dir['baseurl'] . '/')) {
            $file = basename($url);

            $postIds = get_posts([
                'post_type' => 'attachment',
                'post_status' => 'inherit',
                'fields' => 'ids',
                'meta_query'=> [
                    [
                        'value' => $file,
                        'compare' => 'LIKE',
                        'key' => '_wp_attachment_metadata',
                    ],
                ]
            ]);

            if (!empty($postIds)) {
                foreach ($postIds as $postId) {
                    $meta = wp_get_attachment_metadata($postId);

                    $originalFile = basename($meta['file']);
                    $croppedImageFiles = wp_list_pluck($meta['sizes'], 'file');

                    if ($originalFile === $file || in_array($file, $croppedImageFiles)) {
                        $attachmentId = $postId;
                        break;
                    }
                }
            }
        }

        return $attachmentId;
    }

    public function tceGetCopyrightUsageKey(): string
    {
        return $this->copyrightUsageKey;
    }

    public function tceGetCopyrightInformationKey(): string
    {
        return $this->copyrightInformationKey;
    }
}

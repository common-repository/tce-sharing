<?php


namespace TheContentExchange\WpWrappers\WpData;

use InvalidArgumentException;
use TheContentExchange\DTO\TheContentExchangePost as Post;
use TheContentExchange\DTO\TheContentExchangeTag as Tag;

/**
 * Class TheContentExchangeWpPostWrapper
 * @package TheContentExchange\WpWrappers\WpData
 */
class TheContentExchangeWpPostWrapper
{
    /**
     * @param int $id
     * @param string $metaKey
     * @param string $metaValue
     * @param bool $unique
     */
    public function tceAddPostMeta(int $id, string $metaKey, string $metaValue, bool $unique = false): bool
    {
        $currentValue = $this->tceGetPostMeta($id, $metaKey);
        if (isset($currentValue)) {
            $response = update_post_meta($id, $metaKey, $metaValue);
        } else {
            $response = add_post_meta($id, $metaKey, $metaValue, $unique);
        }
        return is_int($response) || true === $response;
    }

    /**
     * @param int $id
     * @param string $metaKey
     * @param bool $singleValue
     */
    public function tceGetPostMeta(int $id, string $metaKey, bool $singleValue = true): string
    {
        return get_post_meta($id, $metaKey, $singleValue);
    }

    /**
     * @param int $id
     */
    private function tceGetPostById(int $id): Post
    {
        return $this->tceConvertWpPostToPost(get_post($id));
    }

    /**
     * @param int $id
     */
    public function tceGetPostAuthorId(int $id): int
    {
        return $this->tceGetPostById($id)->post_author;
    }

    /**
     * @param int $id
     */
    public function tceGetPostContent(int $id): string
    {
        return apply_filters('the_content', strip_shortcodes($this->tceGetPostContentRaw($id)));
    }

    /**
     * @param int $id
     */
    public function tceGetPostBlocks(int $id): array
    {
        return parse_blocks(get_the_content(null, false, $id));
    }

    /**
     * @return Post[]
     */
    public function tceGetUploadedPosts(): array
    {
        return array_map([$this, 'tceConvertWpPostToPost'], get_posts([
            'numberposts' => -1,
            'meta_query' => [
                'relation' => 'AND',
                [
                    'key' => 'sharedWithTce',
                    'value' => 'true'
                ],
                [
                    'relation' => 'OR',
                    [
                        'key' => 'itemMetaId',
                        'compare' => 'NOT EXISTS'
                    ],
                    [
                        'key' => 'itemMetaId',
                        'value' => ''
                    ]
                ]
            ]
        ]));
    }

    /**
     * @param int $id
     */
    public function tceGetPostDate(int $id): string
    {
        return $this->tceGetPostById($id)->post_date;
    }

    /**
     * @param int $id
     */
    public function tceGetPostLastModifiedDate(int $id): string
    {
        return $this->tceGetPostById($id)->post_modified;
    }

    /**
     * @param int $id
     */
    public function tceGetPostStatus(int $id): string
    {
        return get_post_status($id);
    }

    /**
     * @param int $id
     */
    public function tceGetPostTitle(int $id): string
    {
        return $this->tceGetPostById($id)->post_title;
    }

    /**
     * This function will return the post's secondary title when:
     * - The plugin {@link https://wordpress.org/plugins/secondary-title/ Secondary Title} is installed and active.
     * - The post has a secondary title.
     *
     * @param int $id
     *
     * @since 2.0.7
     *
     * @uses has_secondary_title() - {@link https://thaikolja.gitbooks.io/secondary-title/content/functions.html#get-secondary-title Secondary Title}
     * @uses get_secondary_title() - {@link https://thaikolja.gitbooks.io/secondary-title/content/functions.html#hassecondarytitle Secondary Title}
     *
     * @return string - Returns the secondary title when the plugin Secondary Title is installed and initialized
     */
    public function tceGetPostSecondaryTitle(int $id): string
    {
        if ($this->tceCheckIfPluginIsActive('secondary-title/secondary-title.php') &&
            function_exists('has_secondary_title') &&
            function_exists('get_secondary_title')) {
            return has_secondary_title($id) ? get_secondary_title($id) : "";
        }

        return "";
    }

    /**
     * Determines whether a plugin is active.
     *
     * Only plugins installed in the plugins/ folder can be active.
     *
     * Plugins in the mu-plugins/ folder can't be "activated," so this function will
     * return false for those plugins.
     *
     * For more information on this and similar theme functions, check out
     * the {@link https://developer.wordpress.org/themes/basics/conditional-tags/
     * Conditional Tags} article in the Theme Developer Handbook.
     *
     * @param string $plugin - Path to the plugin file relative to the plugins directory. For example: 'some-plugin/some-plugin/php'.
     *
     * @since 2.0.7
     *
     * @uses is_plugin_active()
     */
    public function tceCheckIfPluginIsActive(string $plugin): bool
    {
        return is_plugin_active($plugin);
    }

    /**
     * @param int $id
     */
    public function tceGetFeaturedImageId(int $id): int
    {
        return get_post_thumbnail_id($id);
    }

    /**
     * @param int $id
     * @param bool $leaveName
     * @return string
     */
    public function tceGetPostPermalink(int $id, bool $leaveName = false): string
    {
        return get_permalink($id, $leaveName);
    }

    /**
     * @param int $id
     * @return string
     */
    public function tceGetRelativePostPermalink(int $id): string
    {
        $domain = home_url();
        $permalink = $this->tceGetPostPermalink($id);
        return str_replace($domain, '', $permalink);
    }

    /**
     * @param int $id
     * @return Tag[]
     */
    public function tceGetPostTags(int $id): array
    {
        $wpTags = get_the_tags($id);
        return is_array($wpTags) ? $this->tceConvertWpTagsToTags($wpTags) : [];
    }

    /**
     * @param int $id
     * @return string[]
     */
    public function tceGetPostTagsNamesById(int $id): array
    {
        return array_map(function (Tag $tag) {
            return $tag->name;
        }, $this->tceGetPostTags($id));
    }

    /**
     * @param int $postId
     *
     * @return string
     */
    public function tceGetFeaturedImageUrl(int $postId): ?string
    {
        if (has_post_thumbnail($postId)) {
            return get_the_post_thumbnail_url($postId);
        }
        return null;
    }

    /**
     * Check whether the content is build with a block editor like Gutenberg.
     *
     * @param int $postId
     */
    public function tceIsBlockEditor(int $postId): bool
    {
        if ($this->isClassicEditorPluginActive()) {
            return false;
        }

        if (function_exists('is_block_editor') && is_block_editor()) {
            return true;
        }

        if (function_exists('get_current_screen')) {
            $current_screen = get_current_screen();
            if (method_exists($current_screen, 'is_block_editor') && $current_screen->is_block_editor()) {
                return true;
            }
        }

        if (function_exists('has_blocks') && has_blocks($postId)) {
            return true;
        }

        return false;
    }

    /**
     * Check if Classic Editor plugin is active.
     */
    private function isClassicEditorPluginActive(): bool
    {
        if (!function_exists('is_plugin_active')) {
            include_once ABSPATH . 'wp-admin/includes/plugin.php';
        }

        return is_plugin_active('classic-editor/classic-editor.php');
    }

    /**
     * @param object[] $wpTags
     *
     * @return Tag[]
     */
    private function tceConvertWpTagsToTags(array $wpTags): array
    {
        $tags = [];
        foreach ($wpTags as $wpTag) {
            $tag = new Tag();
            $tag->id = $wpTag->term_id;
            $tag->name = $wpTag->name;
            $tags[] = $tag;
        }

        return $tags;
    }


    /**
     * @param object $wpPost
     */
    private function tceConvertWpPostToPost(object $wpPost): Post
    {
        $post = new Post();
        $post->ID = $wpPost->ID;
        $post->post_author = $wpPost->post_author;
        $post->post_title = $wpPost->post_title;
        $post->post_date = $wpPost->post_date;
        $post->post_modified = $wpPost->post_modified;

        return $post;
    }

    /**
     * @param int $id
     */
    public function tceGetPostContentRaw(int $id): string
    {
        return get_the_content(null, false, $id);
    }
}

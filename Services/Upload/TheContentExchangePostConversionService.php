<?php


namespace TheContentExchange\Services\Upload;

use DateTime;
use DOMDocument;
use DOMXPath;
use JsonException;
use TheContentExchange\Exceptions\TheContentExchangeConfigurationException;
use TheContentExchange\Exceptions\TheContentExchangeCopyrightUsageException;
use TheContentExchange\Exceptions\TheContentExchangeWordPressPostIncompleteException;
use TheContentExchange\Exceptions\TheContentExchangeWordPressPostUnpublishedException;
use TheContentExchange\Services\TCE\TheContentExchangeConfigurationService;
use TheContentExchange\Services\WP\TheContentExchangeWpAttachmentService;
use TheContentExchange\Services\WP\TheContentExchangeWpPostContentParsingService;
use TheContentExchange\Services\WP\TheContentExchangeWpPostCustomizationService;
use TheContentExchange\Services\WP\TheContentExchangeWpPostService;
use TheContentExchange\WpWrappers\WpData\TheContentExchangeWpPostWrapper;
use TheContentExchange\WpWrappers\WpData\TheContentExchangeWpUserWrapper;
use TheContentExchange\WpWrappers\TheContentExchangeWpWrapperFactory;

/**
 * Class TheContentExchangePostConversionService
 *
 * @package TheContentExchange\Services\Upload
 */
class TheContentExchangePostConversionService
{

    /**
     * @var TheContentExchangeWpWrapperFactory
     */
    private $wpWrapperFactory;

    /**
     * @var TheContentExchangeWpPostWrapper;
     */
    private $wpPostWrapper;

    /**
     * @var TheContentExchangeWpUserWrapper
     */
    private $wpUserWrapper;

    /**
     * @var TheContentExchangeConfigurationService
     */
    private $configurationService;

    /**
     * @var TheContentExchangeWpPostContentParsingService
     */
    private $wpPostContentParsingService;

    /**
     * @var TheContentExchangeWpPostCustomizationService
     */
    private $wpPostCustomizationService;

    /**
     * @var TheContentExchangeWpPostService
     */
    private $wpPostService;

    /**
     * @var TheContentExchangeWpAttachmentService
     */
    private $wpAttachmentService;

    /**
     * TheContentExchangePostConversionService constructor.
     *
     * @param TheContentExchangeWpWrapperFactory $wpWrapperFactory
     * @param TheContentExchangeConfigurationService $configurationService
     * @param TheContentExchangeWpPostContentParsingService $wpPostContentParsingService
     * @param TheContentExchangeWpPostCustomizationService $wpPostCustomizationService
     * @param TheContentExchangeWpPostService $wpPostService
     * @param TheContentExchangeWpAttachmentService $wpAttachmentService
     */
    public function __construct(
        TheContentExchangeWpWrapperFactory $wpWrapperFactory,
        TheContentExchangeConfigurationService $configurationService,
        TheContentExchangeWpPostContentParsingService $wpPostContentParsingService,
        TheContentExchangeWpPostCustomizationService $wpPostCustomizationService,
        TheContentExchangeWpPostService $wpPostService,
        TheContentExchangeWpAttachmentService $wpAttachmentService
    ) {
        $this->wpWrapperFactory = $wpWrapperFactory;
        $this->wpPostWrapper = $this->wpWrapperFactory->tceCreateWpPostWrapper();
        $this->wpUserWrapper = $this->wpWrapperFactory->tceCreateWpUserWrapper();

        $this->configurationService = $configurationService;
        $this->wpPostContentParsingService = $wpPostContentParsingService;
        $this->wpPostCustomizationService = $wpPostCustomizationService;
        $this->wpPostService = $wpPostService;
        $this->wpAttachmentService = $wpAttachmentService;
    }

    /**
     * @param int $postId
     *
     * @return string
     * @throws TheContentExchangeConfigurationException
     * @throws TheContentExchangeCopyrightUsageException
     * @throws TheContentExchangeWordPressPostIncompleteException
     * @throws TheContentExchangeWordPressPostUnpublishedException
     * @throws JsonException
     */
    public function tceConvertToApiFormatJson(int $postId): string
    {
        return json_encode(
            $this->tceConvertToApiFormatArray($postId),
            JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT
        );
    }

    /**
     * @param int $postId
     *
     * @return mixed[]
     * @throws TheContentExchangeConfigurationException
     * @throws TheContentExchangeCopyrightUsageException
     * @throws TheContentExchangeWordPressPostIncompleteException
     * @throws TheContentExchangeWordPressPostUnpublishedException
     */
    public function tceConvertToApiFormatArray(int $postId): array
    {
        $postStatus = $this->wpPostWrapper->tceGetPostStatus($postId);
        if ('publish' !== $postStatus) {
            throw new TheContentExchangeWordPressPostUnpublishedException("You are not allowed to upload unpublished posts!");
        }

        // Get source key and organisation id from config
        $sourceKey = $this->configurationService->tceGetSourceKey();
        $organisationId = $this->configurationService->tceGetOrganisationId();

        if (!$sourceKey || !$organisationId) {
            throw new TheContentExchangeConfigurationException("Plugin not configured correctly!");
        }

        return [
          "source_key" => $sourceKey,
          "organisation_id" => $organisationId,
          "item_meta" => $this->tceGetItemMetaInformation($postId),
          "content_meta" => $this->tceGetContentMetaInformation($postId),
          "content_set" => $this->tceGetContentSet($postId),
        ];
    }

    /**
     * @param int $postId
     *
     * @return mixed[]
     * @throws TheContentExchangeWordPressPostIncompleteException
     */
    private function tceGetItemMetaInformation(int $postId): array
    {
        $uri = $this->wpPostWrapper->tceGetPostPermalink($postId);
        if (empty($uri)) {
            throw new TheContentExchangeWordPressPostIncompleteException("The post has no uri!");
        }

        $type = "article";
        $publicationStatus = "usable";
        $id = $this->wpPostService->tceCreateAndSetItemMetaId($postId);

        return [
          "id" => $id,
          "uri" => $uri,
          "type" => $type,
          "publicationStatus" => $publicationStatus,
        ];
    }

    /**
     * @param int $postId
     *
     * @return mixed[]
     * @throws TheContentExchangeWordPressPostIncompleteException
     */
    private function tceGetContentMetaInformation(int $postId): array
    {
        // get content meta information
        $createdAt = $this->wpPostWrapper->tceGetPostDate($postId);
        $createdAt = $this->tceConvertDate($createdAt);

        $lastModifiedAt = $this->wpPostWrapper->tceGetPostLastModifiedDate($postId);
        $lastModifiedAt = $this->tceConvertDate($lastModifiedAt);

        $authorId = $this->wpPostWrapper->tceGetPostAuthorId($postId);
        $author = $this->wpUserWrapper->tceGetUserNameById($authorId);

        $language = "nl";

        $headline = $this->wpPostWrapper->tceGetPostTitle($postId);
        $subline = $this->wpPostWrapper->tceGetPostSecondaryTitle($postId);
        $keywords = $this->wpPostWrapper->tceGetPostTagsNamesById($postId);

        if (empty($author)) {
            throw new TheContentExchangeWordPressPostIncompleteException("The post has no author!");
        }

        if (empty($headline)) {
            throw new TheContentExchangeWordPressPostIncompleteException("The post has no title!");
        }

        return [
          "created_at" => $createdAt,
            // "created_at" => "2017-06-06T00:00:00.000Z",
          "last_modified_at" => $lastModifiedAt,
            // "last_modified_at" => "2017-06-06T00:00:00.000Z",
          "creator" => [
            "name" => $author,
            "uri" => "",
          ],
          "headline" => $headline,
          "subline" => $subline,
          "keywords" => $keywords,
        ];
    }

    private function tceGetXPathFromString($string): DOMXPath
    {
        $domDoc = new DOMDocument();
        @$domDoc->loadHTML($string);
        return new DOMXPath($domDoc);
    }

    private function tceGetImageAttachmentArrayByHtml($html): array
    {
        $xpath = $this->tceGetXPathFromString($html);
        $src = $xpath->evaluate("string(//img/@src)");
        $caption = $xpath->evaluate("string(//figcaption)");
        $id = $this->wpAttachmentService->tceGetAttachmentIdByUrl($src);

        return [
          "uri" => $src,
          "copyright" => $this->wpAttachmentService->tceGetAttachmentCopyright($id),
          "copyright_usage" => $this->tceGetAttachmentCopyrightUsage($id),
          "subline" => $caption,
        ];
    }

    private function tceGetImageAttachmentArray($block): array
    {
        if (isset($block['attrs']['id'])) {
            return $this->tceGetImageAttachmentArrayById($block['attrs']['id']);
        }
        return $this->tceGetImageAttachmentArrayByHtml($block['innerHTML']);
    }

    /**
     * @param $postId
     *
     * @throws TheContentExchangeCopyrightUsageException
     */
    private function tceGetAttachmentCopyrightUsage($postId): string
    {
        $copyrightUsage = $this->wpAttachmentService->tceGetAttachmentCopyrightUsage($postId);

        if ($copyrightUsage === '') {
            throw new TheContentExchangeCopyrightUsageException('The copyright usage is not set for this attachment.');
        }

        return $copyrightUsage;
    }

    private function tceGetImageAttachmentArrayById($id): array
    {
        return [
          "uri" => $this->wpAttachmentService->tceGetAttachmentUrl($id),
          "copyright" => $this->wpAttachmentService->tceGetAttachmentCopyright($id),
          "copyright_usage" => $this->tceGetAttachmentCopyrightUsage($id),
          "subline" => $this->wpAttachmentService->tceGetAttachmentCaption($id),
        ];
    }

    /**
     * @param array $domNodeLists
     *
     * @return array
     */
    private function tceExtractDownloadableMedia(array $domNodeLists): array
    {
        $media = [];

        foreach ($domNodeLists as $domNodeList) {
            foreach ($domNodeList as $domNode) {
                $media[] = [
                    "share_method" => "download",
                    "uri" => $domNode->getAttribute("src")
                ];
            }
        }

        return $media;
    }

    /**
     * @param int $postId
     *
     * @return mixed[]
     * @throws TheContentExchangeCopyrightUsageException
     */
    private function tceGetContentSet(int $postId): array
    {
        $imageArray = [];
        $videoArray = [];
        $audioArray = [];

        if ($featuredImage = $this->wpPostWrapper->tceGetFeaturedImageUrl($postId)) {
            $featuredImageId = $this->wpPostWrapper->tceGetFeaturedImageId($postId);
            $imageArray[] = [
                'uri' => $featuredImage,
                'copyright' => $this->wpAttachmentService->tceGetAttachmentCopyright($featuredImageId),
                "copyright_usage" => $this->tceGetAttachmentCopyrightUsage($featuredImageId),
            ];
        }

        if ($this->wpPostWrapper->tceIsBlockEditor($postId)) {
            $blocks = $this->wpPostWrapper->tceGetPostBlocks($postId);
            foreach ($blocks as $block) {
                switch ($block['blockName']) {
                    case 'core/image':
                        $imageArray[] = $this->tceGetImageAttachmentArray($block);
                        break;

                    case 'core/gallery':
                        foreach ($block['attrs']['ids'] as $id) {
                            $imageArray[] = $this->tceGetImageAttachmentArrayById($id);
                        }
                        break;

                    case 'core/video':
                        $id = $block['attrs']['id'];
                        $videoArray[] = [
                            "share_method" => "download",
                            "subline" => $this->wpAttachmentService->tceGetAttachmentCaption($id),
                            "uri" => $this->wpAttachmentService->tceGetAttachmentUrl($id),
                        ];
                        break;

                    case 'core/audio':
                        $id = $block['attrs']['id'];
                        $audioArray[] = [
                            "share_method" => "download",
                            "subline" => $this->wpAttachmentService->tceGetAttachmentCaption($id),
                            "uri" => $this->wpAttachmentService->tceGetAttachmentUrl($id),
                        ];
                        break;

                    // Support for WordPress 5.5 and earlier
                    case 'core-embed/youtube':
                    case 'core-embed/vimeo':
                        $videoArray[] = [
                            "share_method" => "embed",
                            "uri" => $block['attrs']['url'],
                        ];
                        break;

                    // Support for WordPress 5.6 and later
                    case 'core/embed':
                        $type = $block['attrs']['type'];
                        $provider = $block['attrs']['providerNameSlug'];
                        $acceptedProviders = ['youtube', 'vimeo'];
                        if ($type === 'video' && in_array($provider, $acceptedProviders)) {
                            $videoArray[] = [
                                "share_method" => "embed",
                                "uri" => $block['attrs']['url'],
                            ];
                        }
                        break;
                }
            }
        } else { // Fallback for when the Gutenberg editor is disabled.
            $postContent = $this->wpPostWrapper->tceGetPostContent($postId);

            $videoArray = $this->tceExtractDownloadableMedia([
                $this->tceGetXPathFromString($postContent)->query("//video[@src]"),
                $this->tceGetXPathFromString($postContent)->query("//video/source[@src]")
            ]);

            $audioArray = $this->tceExtractDownloadableMedia([
                $this->tceGetXPathFromString($postContent)->query("//audio[@src]"),
                $this->tceGetXPathFromString($postContent)->query("//audio/source[@src]")
            ]);

            foreach ($this->tceGetXPathFromString($postContent)->query("//img[@src]") as $imageEntry) {
                $image_src = $imageEntry->getAttribute("src");
                $image_id = $this->wpAttachmentService->tceGetAttachmentIdByUrl($image_src);
                $imageArray[] = [
                    "uri" => $image_src,
                    "copyright" => $this->wpAttachmentService->tceGetAttachmentCopyright($image_id),
                    "copyright_usage" => $this->tceGetAttachmentCopyrightUsage($image_id),
                    "subline" => $imageEntry->getAttribute("alt")
                ];
            }
            // Support embedded iframes
            foreach ($this->tceGetXPathFromString($postContent)->query("//iframe[@src]") as $iframeEntry) {
                $iframeEntrySrc = $iframeEntry->getAttribute("src");
                if (getimagesize($iframeEntrySrc)) {
                    $imageArray[] = [
                        "uri" => $iframeEntrySrc
                    ];
                } else {
                    $videoArray[] = [
                        "share_method" => "embed", // Treat an iframe with a video as an embedded source
                        "uri" => $iframeEntrySrc
                    ];
                }
            }
        }

        return [
          "text" => [
            [
              "content_type" => "text/html",
              "value" => $this->wpPostWrapper->tceGetPostContent($postId),
            ],
          ],
          "images" => $imageArray,
          "videos" => $videoArray,
          "audios" => $audioArray,
        ];
    }

    /**
     * @param string $wpDate
     *
     * @return string|false
     */
    public function tceConvertDate(string $wpDate): string
    {
        $date = strtotime($wpDate);
        return date(DateTime::ATOM, $date);
    }
}

<?php


namespace TheContentExchange\Services\WP;

use DOMDocument;
use DOMElement;
use DOMNode;

/**
 * Class TheContentExchangeWpPostContentParsingService
 * @package TheContentExchange\Services\WP
 */
class TheContentExchangeWpPostContentParsingService
{

    /**
     * TheContentExchangeWpPostContentParsingService constructor.
     */
    public function __construct()
    {
    }

    /**
     * @param string $postContent
     * @return string[]
     */
    public function tceGetImageUrls(string $postContent): array
    {
        $imageUrls = [];

        $dom = new DOMDocument;

        // Disable errors temporarily to keep log clean
        libxml_use_internal_errors(true);
        if ($dom->loadHTML($postContent)) {
            $images = $dom->getElementsByTagName('img');
            foreach ($images as $image) {
                if ($image->getAttribute('src')) {
                    $imageUrls[] = $image->getAttribute('src');
                }
            }
        }
        libxml_clear_errors();

        return $imageUrls;
    }

    /**
     * @param string $postContent
     * @return mixed[]
     */
    public function tceGetVideos(string $postContent): array
    {
        $videos = [];

        $dom = new DOMDocument;
        libxml_use_internal_errors(true);
        if ($dom->loadHTML($postContent)) {
            $figures = $dom->getElementsByTagName('figure');
            foreach ($figures as $figure) {
                if ($this->tceFigureIsUploadedVideo($figure)) {
                    $videos[] = [
                        "share_method" => "download",
                        "headline" => $this->tceGetVideoCaption($figure),
                        "uri" => $this->tceGetUploadedVideoSource($figure)
                    ];
                }

                if ($this->tceFigureIsEmbeddedVideo($figure)) {
                    $videos[] = [
                        "share_method" => "embed",
                        "headline" => $this->tceGetEmbeddedVideoTitle($figure),
                        "subline" => $this->tceGetVideoCaption($figure),
                        "uri" => $this->tceGetEmbeddedVideoSource($figure)
                    ];
                }
            }
        }
        libxml_clear_errors();

        return $videos;
    }

    private function tceFigureIsUploadedVideo(DOMElement $figure): bool
    {
        $searchedClass = "wp-block-video";
        $figureClasses = $figure->getAttribute('class');

        return strpos($figureClasses, $searchedClass) !== false;
    }

    private function tceFigureIsEmbeddedVideo(DOMElement $figure): bool
    {
        $searchedClass = "wp-block-embed is-type-video";
        $figureClasses = $figure->getAttribute('class');

        return strpos($figureClasses, $searchedClass) !== false;
    }

    private function tceGetVideoCaption(DOMElement $figure): string
    {
        $captions = $figure->getElementsByTagName('figcaption');

        return (0 === $captions->count()) ? "" : $this->tceGetInnerHTML($captions->item(0));
    }

    private function tceGetInnerHTML(DOMNode $element): string
    {
        $doc = $element->ownerDocument;
        $html = '';

        foreach ($element->childNodes as $node) {
            $html .= $doc->saveHTML($node);
        }

        return $html;
    }

    private function tceGetUploadedVideoSource(DOMElement $figure): string
    {
        $videos = $figure->getElementsByTagName('video');
        $source = $videos[0]->getAttribute('src');

        // Remove new line characters from source
        $source = trim(preg_replace('/\s+/', '', $source));

        return $source;
    }

    private function tceGetEmbeddedVideoSource(DOMElement $figure): string
    {
        $iframes = $figure->getElementsByTagName('iframe');

        return $iframes->count() ? $iframes[0]->getAttribute('src') : "";
    }

    private function tceGetEmbeddedVideoTitle(DOMElement $figure): string
    {
        $iframes = $figure->getElementsByTagName('iframe');

        return $iframes->count() ? $iframes[0]->getAttribute('title') : "";
    }
}

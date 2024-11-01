<?php


namespace TheContentExchange\Services\WP;

/**
 * Class TheContentExchangeWpPostCustomizationService
 * @package TheContentExchange\Services\WP
 */
class TheContentExchangeWpPostCustomizationService
{
    /**
     * @var TheContentExchangeWpPostService
     */
    private $wpPostService;

    /**
     * TheContentExchangeWpPostCustomizationService constructor.
     *
     * @param TheContentExchangeWpPostService $wpPostService
     */
    public function __construct(TheContentExchangeWpPostService $wpPostService)
    {
        $this->wpPostService = $wpPostService;
    }

    /**
     * @param string[] $columns
     * @return string[]
     */
    public function tceRegisterSharedWithTceColumn(array $columns): array
    {
        $columns['sharedWithTce'] = 'Shared with TCE';

        return $columns;
    }

    /**
     * @param string $columnKey
     * @param int $postId
     */
    public function tceRenderSharedWithTceColumn(string $columnKey, int $postId): void
    {
        if ('sharedWithTce' === $columnKey) {
            $shared = $this->wpPostService->tceGetIsShared($postId);
            $iconClasses = 'dashicons ' . ($shared ? 'dashicons-yes-alt' : 'dashicons-no-alt' );
            $style = 'color: ' . ( $shared ? 'green' : 'red' ) . ';';

            echo "<span style='" . $style . "' class='" . $iconClasses . "'></span>";
        }
    }

    public function tceStyleSharedWithTceColumn(): void
    {
        ?>
        <style type="text/css">
            .column-sharedWithTce { width:70px; overflow:hidden; text-align: center;}
        </style>
        <?php
    }
}

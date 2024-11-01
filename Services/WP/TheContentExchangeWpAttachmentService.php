<?php

namespace TheContentExchange\Services\WP;

use TheContentExchange\Exceptions\TheContentExchangeCopyrightUsageException;
use TheContentExchange\Services\TCE\TheContentExchangeConfigurationService;
use TheContentExchange\WpWrappers\TheContentExchangeWpWrapperFactory;
use TheContentExchange\WpWrappers\WpData\TheContentExchangeWpAttachmentWrapper;

/**
 * Class TheContentExchangeWpAttachmentService
 * @package TheContentExchange\Services\WP
 */
class TheContentExchangeWpAttachmentService
{
    /**
     * @var TheContentExchangeWpWrapperFactory
     */
    private $wpWrapperFactory;

    /**
     * @var TheContentExchangeWpAttachmentWrapper
     */
    private $wpAttachmentWrapper;

    /**
     * @var TheContentExchangeConfigurationService
     */
    private $configurationService;

    /**
     * TheContentExchangeWpAttachmentService constructor.
     *
     * @param TheContentExchangeWpWrapperFactory $wpWrapperFactory
     * @param TheContentExchangeConfigurationService $configurationService
     */
    public function __construct(
        TheContentExchangeWpWrapperFactory $wpWrapperFactory,
        TheContentExchangeConfigurationService $configurationService
    ) {
        $this->wpWrapperFactory     = $wpWrapperFactory;
        $this->wpAttachmentWrapper  = $this->wpWrapperFactory->tceCreateWpAttachmentWrapper();
        $this->configurationService = $configurationService;
    }

    /**
     * Returns the copyright value. If no value is set, the name of the organisation will be returned.
     *
     * @param int $attachmentId
     */
    public function tceGetAttachmentCopyright(int $attachmentId): string
    {
        $attachmentCopyright = $this->tceGetAttachmentCopyrightInformationValue($attachmentId);

        return $attachmentCopyright !== '' ? $attachmentCopyright : $this->configurationService->tceGetOrganisationName();
    }

    /**
     * Returns the copyright usage for an attachment.
     *
     * @param int $attachmentId
     */
    public function tceGetAttachmentCopyrightUsage(int $attachmentId): string
    {
        return $this->wpAttachmentWrapper->tceGetAttachmentCopyrightUsage($attachmentId) ?:
                          $this->configurationService->tceGetCopyrightUsage();
    }

    /**
     * @param string $url
     */
    public function tceGetAttachmentIdByUrl(string $url): int
    {
        return $this->wpAttachmentWrapper->tceGetAttachmentIdByUrl($url);
    }

    /**
     * @param int $id
     */
    public function tceGetAttachmentUrl(int $id): string
    {
        return $this->wpAttachmentWrapper->tceGetAttachmentUrl($id);
    }

    /**
     * @param int $id
     */
    public function tceGetAttachmentCaption(int $id): string
    {
        return $this->wpAttachmentWrapper->tceGetAttachmentCaption($id);
    }

    /**
     * @param int $id
     */
    public function tceGetAttachmentCopyrightInformationValue(int $id): string
    {
        return $this->wpAttachmentWrapper->tceGetAttachmentCopyrightInformationValue($id);
    }

    /**
     * @param int $id
     * @param string $value
     */
    public function tceSetAttachmentCopyrightInformationValue(int $id, string $value): void
    {
        $this->wpAttachmentWrapper->tceSetAttachmentCopyrightInformationValue($id, $value);
    }

    /**
     * @param int $id
     * @param string $value
     */
    public function tceSetAttachmentCopyrightUsageValue(int $id, string $value): void
    {
        $this->wpAttachmentWrapper->tceSetAttachmentCopyrightUsageValue($id, $value);
    }

    public function tceGetCopyrightUsageKey(): string
    {
        return $this->wpAttachmentWrapper->tceGetCopyrightUsageKey();
    }

    public function tceGetCopyrightInformationKey(): string
    {
        return $this->wpAttachmentWrapper->tceGetCopyrightInformationKey();
    }
}

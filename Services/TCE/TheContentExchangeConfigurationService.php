<?php


namespace TheContentExchange\Services\TCE;

use TheContentExchange\WpWrappers\WpData\TheContentExchangeWpOptionsWrapper;
use TheContentExchange\WpWrappers\TheContentExchangeWpWrapperFactory;

/**
 * Class TheContentExchangeConfigurationService
 * @package TheContentExchange\Services\TCE
 */
class TheContentExchangeConfigurationService
{
    /**
     * @var TheContentExchangeWpOptionsWrapper
     */
    private $wpOptionsWrapper;

    /**
     * @var string[]
     */
    private $configurationOptions = [
        'organisationId',
        'organisationName',
        'sourceKey',
        'sourceName',
        'autoUploadDefault',
        'defaultAttachmentCopyright',
        'copyrightUsage'
    ];

    /**
     * TheContentExchangeConfigurationService constructor.
     *
     * @param TheContentExchangeWpWrapperFactory $wpWrapperFactory
     */
    public function __construct(TheContentExchangeWpWrapperFactory $wpWrapperFactory)
    {
        $this->wpOptionsWrapper = $wpWrapperFactory->tceCreateWpOptionsWrapper();
    }

    /**
     * Init all configuration options.
     *
     * @return void
     */
    public function tceInitOptions(): void
    {
        foreach ($this->configurationOptions as $option) {
            $this->wpOptionsWrapper->tceInitOption($option);
        }
    }

    /**
     * Update the organisation id.
     *
     * @param string $optionValue
     */
    public function tceUpdateOrganisationId(string $optionValue): void
    {
        $this->wpOptionsWrapper->tceUpdateOptionValue("organisationId", $optionValue);
    }

    /**
     * Update the organisation name.
     *
     * @param string $optionValue
     */
    public function tceUpdateOrganisationName(string $optionValue): void
    {
        $this->wpOptionsWrapper->tceUpdateOptionValue("organisationName", $optionValue);
    }

    /**
     * update the source key.
     *
     * @param string $optionValue
     */
    public function tceUpdateSourceKey(string $optionValue): void
    {
        $this->wpOptionsWrapper->tceUpdateOptionValue("sourceKey", $optionValue);
    }

    /**
     * update the source name.
     *
     * @param string $optionValue
     */
    public function tceUpdateSourceName(string $optionValue): void
    {
        $this->wpOptionsWrapper->tceUpdateOptionValue('sourceName', $optionValue);
    }

    /**
     * update the auto upload default value.
     *
     * @param string $optionValue
     */
    public function tceUpdateAutoUploadDefault(string $optionValue): void
    {
        $this->wpOptionsWrapper->tceUpdateOptionValue("autoUploadDefault", $optionValue);
    }

    /**
     * @param string|null $optionValue
     */
    public function tceUpdateDefaultAttachmentCopyright(?string $optionValue): void
    {
        if ($optionValue === null) {
            $this->wpOptionsWrapper->tceDeleteOption('defaultAttachmentCopyright');
            return;
        }
        $this->wpOptionsWrapper->tceUpdateOptionValue("defaultAttachmentCopyright", $optionValue);
    }

    /**
     * @param string $optionValue
     */
    public function tceUpdateCopyrightUsage(string $optionValue): void
    {
        $this->wpOptionsWrapper->tceUpdateOptionValue("copyrightUsage", $optionValue);
    }

    /**
     * Get the organisation name.
     *
     * @return string
     */
    public function tceGetOrganisationName(): string
    {
        return $this->wpOptionsWrapper->tceGetOptionValue("organisationName");
    }

    /**
     * Get the organisation id.
     *
     * @return string
     */
    public function tceGetOrganisationId(): string
    {
        return $this->wpOptionsWrapper->tceGetOptionValue("organisationId");
    }

    /**
     * Get the source key.
     *
     * @return string
     */
    public function tceGetSourceKey(): string
    {
        return $this->wpOptionsWrapper->tceGetOptionValue("sourceKey");
    }

    /**
     * Get the source name.
     *
     * @return string
     */
    public function tceGetSourceName(): string
    {
        return $this->wpOptionsWrapper->tceGetOptionValue('sourceName');
    }

    /**
     * Get the auto upload default value.
     *
     * @return string
     */
    public function tceGetAutoUploadDefault(): string
    {
        return $this->wpOptionsWrapper->tceGetOptionValue("autoUploadDefault");
    }

    /**
     * Get the default attachment copyright value.
     */
    public function tceGetDefaultAttachmentCopyright(): string
    {
        return $this->wpOptionsWrapper->tceGetOptionValue('defaultAttachmentCopyright');
    }

    /**
     * Get the copyright usage value.
     */
    public function tceGetCopyrightUsage(): string
    {
        return $this->wpOptionsWrapper->tceGetOptionValue('copyrightUsage');
    }

    /**
     * Delete all configuration options.
     */
    public function tceDeleteOptions(): void
    {
        foreach ($this->configurationOptions as $option) {
            $this->wpOptionsWrapper->tceDeleteOption($option);
        }
    }

    /**
     * Check if the organisation id and source key are configured.
     *
     * @return bool
     */
    public function tceIsConfigured(): bool
    {
        return !empty($this->tceGetOrganisationId()) && !empty($this->tceGetSourceKey());
    }

    /**
     * Get the unique prefix, used for each option key.
     *
     * @return string
     */
    public function tceGetOptionsPrefix(): string
    {
        return $this->wpOptionsWrapper->tceGetOptionsPrefix();
    }
}

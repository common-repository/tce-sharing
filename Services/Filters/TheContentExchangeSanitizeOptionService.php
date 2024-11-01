<?php

namespace TheContentExchange\Services\Filters;

use TheContentExchange\Services\TCE\TheContentExchangeConfigurationService;
use TheContentExchange\WpWrappers\WpFilters\TheContentExchangeWpSanitizeWrapper;
use TheContentExchange\WpWrappers\TheContentExchangeWpWrapperFactory;

/**
 * Class TheContentExchangeSanitizeOptionService
 * @package TheContentExchange\Services\Filters
 */
class TheContentExchangeSanitizeOptionService
{
    /**
     * @var TheContentExchangeWpWrapperFactory
     */
    private $wpWrapperFactory;

    /**
     * @var TheContentExchangeWpSanitizeWrapper
     */
    private $wpSanitizeWrapper;

    /**
     * @var TheContentExchangeInputFilterService
     */
    private $tceInputFilterService;

    /**
     * @var TheContentExchangeConfigurationService
     */
    private $tceConfigurationService;

    /**
     * @var string
     */
    private $optionPrefix;

    /**
     * TheContentExchangeSanitizeOptionService constructor.
     *
     * @param TheContentExchangeWpWrapperFactory $wpWrapperFactory
     * @param TheContentExchangeInputFilterService $tceInputFilterService
     * @param TheContentExchangeConfigurationService $tceConfigurationService
     */
    public function __construct(
        TheContentExchangeWpWrapperFactory $wpWrapperFactory,
        TheContentExchangeInputFilterService $tceInputFilterService,
        TheContentExchangeConfigurationService $tceConfigurationService
    ) {
        $this->wpWrapperFactory = $wpWrapperFactory;
        $this->wpSanitizeWrapper = $this->wpWrapperFactory->tceCreateWpSanitizeWrapper();

        $this->tceInputFilterService = $tceInputFilterService;
        $this->tceConfigurationService = $tceConfigurationService;
        $this->optionPrefix = $this->tceConfigurationService->tceGetOptionsPrefix();
    }

    /**
     * Sanitize 'accessToken' from a POST request.
     */
    public function tceSanitizeOptionAccessToken(): string
    {
        return $this->tceSanitizeOptionPostRequest('accessToken', 'accessToken');
    }

    /**
     * Sanitize 'idToken' from a POST request.
     */
    public function tceSanitizeOptionIdToken(): ?string
    {
        return $this->tceSanitizeOptionPostRequest('idToken', 'idToken');
    }

    /**
     * Sanitize 'refreshToken' from a POST request.
     */
    public function tceSanitizeOptionRefreshToken(): ?string
    {
        return $this->tceSanitizeOptionPostRequest('refreshToken', 'refreshToken');
    }

    /**
     * Sanitize 'organisationName' from a POST request.
     */
    public function sanitizeOptionOrganisationName(): ?string
    {
        return $this->tceSanitizeOptionPostRequest('organisationName', 'organisationName');
    }

    /**
     * Sanitize 'organisationId' from a POST request.
     */
    public function tceSanitizeOptionOrganisationId(): ?string
    {
        return $this->tceSanitizeOptionPostRequest('organisationId', 'organisationId');
    }

    /**
     * Sanitize 'sourceKey' from a POST request.
     */
    public function tceSanitizeOptionSourceKey(): ?string
    {
        return $this->tceSanitizeOptionPostRequest('sourceKey', 'sourceKey');
    }

    /**
     * Sanitize 'sourceName' from a POST request.
     */
    public function tceSanitizeOptionSourceName(): ?string
    {
        return $this->tceSanitizeOptionPostRequest('sourceName', 'sourceName');
    }

    /**
     * Sanitize 'autoUploadDefault' from a POST request.
     */
    public function tceSanitizeOptionAutoUploadDefault(): ?string
    {
        return $this->tceSanitizeOptionPostRequest('autoUploadDefault', 'autoUploadDefault');
    }

    /**
     * Sanitize 'defaultAttachmentCopyright' from a POST request.
     */
    public function tceSanitizeOptionDefaultAttachmentCopyright(): ?string
    {
        return trim($this->tceSanitizeOptionPostRequest('defaultAttachmentCopyright', 'defaultAttachmentCopyright'));
    }

    /**
     * Sanitize 'copyrightUsage' from a POST request.
     */
    public function tceSanitizeOptionCopyrightUsage(): ?string
    {
        return $this->tceSanitizeOptionPostRequest('copyrightUsage', 'copyrightUsage');
    }

    /**
     * Sanitize 'autoUploadPost' from a POST request.
     */
    public function tceSanitizeOptionAutoUploadPost(): ?string
    {
        return $this->tceSanitizeMetaPostRequest('autoUploadPost', 'autoUploadPost');
    }

    /**
     * Sanitize a Post Request value for a Wordpress option.
     *
     * @param string $fieldName - The name of the field used in the form.
     * @param string $inputName - Name of a variable to get.
     * @param int $filter - The ID of the filter to apply.
     */
    private function tceSanitizeOptionPostRequest(string $fieldName, string $inputName, $filter = FILTER_SANITIZE_STRING): ?string
    {
        return $this->tceSanitize($fieldName, $inputName, 'option', 'post', $filter);
    }

    /**
     * Sanitize a Post Request value for a Wordpress post meta value.
     *
     * @param string $fieldName - The name of the Metadata key or Option without the TCE prefix.
     * @param string $inputName - Name of a variable to get.
     * @param int $filter - The ID of the filter to apply.
     */
    private function tceSanitizeMetaPostRequest(string $fieldName, string $inputName, $filter = FILTER_SANITIZE_STRING): ?string
    {
        return $this->tceSanitize($fieldName, $inputName, 'meta', 'post', $filter);
    }

    /**
     * Gets a specific external variable by name and filters it.
     *
     * @param string $fieldName - The name of the Metadata key or Option without the TCE prefix.
     * @param string $inputName - Name of a variable to get.
     * @param string $fieldType - meta || option, According to whether it is a meta or option field.
     * @param string $inputType - get || post, According to whether it is a GET or POST request.
     * @param int $filter - The ID of the filter to apply.
     */
    private function tceSanitize(string $fieldName, string $inputName, string $fieldType, string $inputType, int $filter): ?string
    {
        $name = $this->optionPrefix . $fieldName;
        $value = '';
        switch ($inputType) {
            case 'get':
                $value = $this->tceInputFilterService->tceFilterGetInput($inputName, $filter);
                break;
            case 'post':
                $value = $this->tceInputFilterService->tceFilterPostInput($inputName, $filter);
                break;
        }
        switch ($fieldType) {
            case 'option':
                return $this->wpSanitizeWrapper->tceSanitizeOptions($name, $value);
            case 'meta':
                return $this->wpSanitizeWrapper->tceSanitizeMeta($name, $value);
        }
    }
}

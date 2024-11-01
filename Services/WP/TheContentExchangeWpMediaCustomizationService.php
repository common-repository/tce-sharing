<?php

namespace TheContentExchange\Services\WP;

use TheContentExchange\Services\Filters\TheContentExchangeInputFilterService;
use TheContentExchange\Services\Notices\TheContentExchangeNoticesService;
use TheContentExchange\Services\TCE\TheContentExchangeConfigurationService;
use TheContentExchange\WpWrappers\WpData\TheContentExchangeWpUserWrapper;
use TheContentExchange\WpWrappers\TheContentExchangeWpWrapperFactory;

/**
 * Class TheContentExchangeWpMediaCustomizationService
 * @package TheContentExchange\Services\WP
 */
class TheContentExchangeWpMediaCustomizationService
{
    /**
     * @var TheContentExchangeWpWrapperFactory
     */
    private $wpWrapperFactory;

    /**
     * @var TheContentExchangeWpUserWrapper
     */
    private $wpUserWrapper;

    /**
     * @var TheContentExchangeConfigurationService
     */
    private $tceConfigurationService;

    /**
     * @var TheContentExchangeWpUrlService
     */
    private $wpUrlService;

    /**
     * @var TheContentExchangeNoticesService
     */
    private $tceNoticesService;

    /**
     * @var TheContentExchangeWpViewService
     */
    private $wpViewService;

    /**
     * @var TheContentExchangeWpAttachmentService
     */
    private $wpAttachmentService;

    /**
     * @var TheContentExchangeInputFilterService
     */
    private $tceInputFilterService;

    /**
     * @var string
     */
    private $copyrightUsageKey;

    /**
     * @var string
     */
    private $copyrightInformationKey;

    /**
     * TheContentExchangeWpMediaCustomizationService constructor.
     *
     * @param TheContentExchangeWpWrapperFactory $wpWrapperFactory
     * @param TheContentExchangeConfigurationService $tceConfigurationService
     * @param TheContentExchangeWpUrlService $wpUrlService
     * @param TheContentExchangeNoticesService $tceNoticesService
     * @param TheContentExchangeWpViewService $wpViewService
     * @param TheContentExchangeWpAttachmentService $wpAttachmentService
     * @param TheContentExchangeInputFilterService $tceInputFilterService
     */
    public function __construct(
        TheContentExchangeWpWrapperFactory $wpWrapperFactory,
        TheContentExchangeConfigurationService $tceConfigurationService,
        TheContentExchangeWpUrlService $wpUrlService,
        TheContentExchangeNoticesService $tceNoticesService,
        TheContentExchangeWpViewService $wpViewService,
        TheContentExchangeWpAttachmentService $wpAttachmentService,
        TheContentExchangeInputFilterService $tceInputFilterService
    ) {
        $this->wpWrapperFactory = $wpWrapperFactory;
        $this->tceConfigurationService = $tceConfigurationService;
        $this->wpUrlService = $wpUrlService;
        $this->tceNoticesService = $tceNoticesService;
        $this->wpViewService = $wpViewService;
        $this->wpAttachmentService = $wpAttachmentService;
        $this->tceInputFilterService = $tceInputFilterService;

        $this->wpUserWrapper = $this->wpWrapperFactory->tceCreateWpUserWrapper();

        $this->copyrightUsageKey = $this->wpAttachmentService->tceGetCopyrightUsageKey();
        $this->copyrightInformationKey = $this->wpAttachmentService->tceGetCopyrightInformationKey();
    }

    public function tceRegisterCopyrightUsageRadioButtons(array $formFields, object $post): array
    {
        $copyrightUsage = esc_attr($this->wpAttachmentService->tceGetAttachmentCopyrightUsage($post->ID));
        $options = array(
            'included'   => __(
                'The attached images are included in the content item license.' . $this->getIncludedTooltipHtml()
            ),
            'licensed'   => __(
                'The attached images are not included, and require a separate license for publication.' . $this->getLicensedTooltipHtml()
            ),
        );

        $html = array();

        foreach ($options as $optionName => $optionLabel) {
            $optionName  = esc_attr($optionName);
            $isChecked = $copyrightUsage === $optionName ? " checked='checked'" : '';
            $html[] = "<label 
                         for='attachments-{$this->copyrightUsageKey}-{$optionName}' 
                         class='attachments-{$this->copyrightUsageKey}-label'>
                             <input 
                                type='radio' 
                                name='attachments[{$post->ID}][{$this->copyrightUsageKey}]' 
                                id='attachments-{$this->copyrightUsageKey}-{$optionName}' 
                                value='$optionName'
                                {$isChecked}
                             />
                             {$optionLabel}
                        </label>";
        }

        $formFields[$this->copyrightUsageKey] = [
          'label' => 'Copyright:',
          'input' => 'html',
          'html' => implode("", $html),
          'required' => true
        ];


        return $formFields;
    }

    /**
     * @param array $post
     * @param array $attachment
     */
    public function tceSaveCopyrightUsageRadioButtons(array $post, array $attachment): array
    {
        $value = $attachment[$this->copyrightUsageKey];

        $this->wpAttachmentService->tceSetAttachmentCopyrightUsageValue($post['ID'], $value);

        return $post;
    }

    /**
     * @param array $formFields
     * @param object $post
     */
    public function tceRegisterCopyrightInput(array $formFields, object $post): array
    {
        $value = esc_attr($this->wpAttachmentService->tceGetAttachmentCopyrightInformationValue($post->ID)) ?: '';
        $formFields[$this->copyrightInformationKey] = [
            'label' => 'Copyright holder:',
            'input' => 'text',
            'value' => $value,
            'required' => true
        ];

        return $formFields;
    }

    /**
     * @param array $post
     * @param array $attachment
     */
    public function tceSaveCopyrightInput(array $post, array $attachment): array
    {
        $metaValue = $this->wpAttachmentService->tceGetAttachmentCopyrightInformationValue($post['ID']);
        if (isset($attachment[$this->copyrightInformationKey])) {
            $metaValue = sanitize_text_field($attachment[$this->copyrightInformationKey]);
        }
        $this->wpAttachmentService->tceSetAttachmentCopyrightInformationValue(
            $post['ID'],
            $metaValue
        );

        return $post;
    }

    /**
     * @param int $postId
     */
    public function tceAddAttachmentCopyrightDefaultValues(int $postId): void
    {
        $this->wpAttachmentService->tceSetAttachmentCopyrightUsageValue(
            $postId,
            $this->tceConfigurationService->tceGetCopyrightUsage()
        );
        $this->wpAttachmentService->tceSetAttachmentCopyrightInformationValue(
            $postId,
            $this->tceConfigurationService->tceGetDefaultAttachmentCopyright()
        );
    }

    public function tceRegisterBulkCopyright(array $bulkActions): array
    {
        if (!$this->wpUserWrapper->tceCheckIfUserHasRightTo('upload_files')) {
            return $bulkActions;
        }

        $bulkActions['tceEditCopyright'] = 'Add default copyright';

        return $bulkActions;
    }

    public function tceHandleBulkCopyright(string $redirectTo, $doAction, array $attachmentIds): string
    {
        if ($doAction !== 'tceEditCopyright') {
            return $redirectTo;
        }

        $totalAttachmentCount = count($attachmentIds);
        $editedAttachmentCount = 0;

        foreach ($attachmentIds as $id) {
            $this->tceAddAttachmentCopyrightDefaultValues($id);
            $editedAttachmentCount++;
        }

        $redirectTo = $this->wpUrlService->tceAddQueryArgsToUrl([
            "total" => $totalAttachmentCount,
            "success" => $editedAttachmentCount
        ], $redirectTo);

        return $redirectTo;
    }

    public function tceNotifyEditAttachmentCopyrightStatus(): void
    {
        $total = $this->tceInputFilterService->tceFilterGetInput('total', FILTER_SANITIZE_NUMBER_INT);
        $success = $this->tceInputFilterService->tceFilterGetInput('success', FILTER_SANITIZE_NUMBER_INT);
        if ($this->tcePageIsActive() && $total > 0) {
            if ($success === 0) {
                $this->tceNoticesService->tceAddError(
                    'TCE Sharing - Bulk edit failed ',
                    'Detailed error information',
                    $this->wpUrlService->tceGetCustomWpAdminPageUrl('tce-sharing-configuration')
                );
            } elseif ($success < $total) {
                $this->tceNoticesService->tceAddWarning(
                    'TCE Sharing - Bulk edit partially succeeded (' . $success . '/' . $total . ') ',
                    'Detailed error information',
                    $this->wpUrlService->tceGetCustomWpAdminPageUrl('tce-sharing-configuration')
                );
            } else {
                $this->tceNoticesService->tceAddSuccess(
                    'TCE Sharing - Bulk edit successful'
                );
            }

            $this->tceNoticesService->tceShowNotices();
        }
    }

    private function tcePageIsActive(): bool
    {
        return $this->wpViewService->tceCheckCurrentPage("upload");
    }

    private function getIncludedTooltipHtml(): string
    {
        return '<div class="explanation-tooltip">
                    You have the redistribution rights of these images and you allow the buyer of the content item to 
                    redistribute the attached images together with the content item.
                </div>';
    }

    private function getLicensedTooltipHtml(): string
    {
        return '<div class="explanation-tooltip">
                    You don\'t have the redistribution rights of these images. The buyer needs to obtain a license 
                    from the copyright holder directly (i.e. Getty Images / Adobe Stock / etc).
                </div>';
    }
}

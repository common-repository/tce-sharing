<?php

use TheContentExchange\Services\TCE\TheContentExchangeSessionService;

$organisationId = esc_attr($this->configurationService->tceGetOrganisationId());
$organisationName = esc_attr($this->configurationService->tceGetOrganisationName());
$sourceKey = esc_attr($this->configurationService->tceGetSourceKey());
$sourceName = esc_attr($this->configurationService->tceGetSourceName());
$autoUploadDefault = esc_attr($this->configurationService->tceGetAutoUploadDefault());
$hasCredentials = $this->tceSessionService->tceIsConnected();
$attachmentCopyrightHolder = esc_attr($this->configurationService->tceGetDefaultAttachmentCopyright());
$attachmentCopyrightUsage = esc_attr($this->configurationService->tceGetCopyrightUsage());
$pluginDirUrl = esc_url(THE_CONTENT_EXCHANGE_BASE_URL . 'Views/' . $this->viewName . '/js/');
?>
<script type="application/javascript">
    //<![CDATA[
    var INITIAL_STATE = {
        source: <?php echo ($sourceKey && '' !== $sourceKey) ? "'$sourceKey'" : 'null'; ?>,
        sourceName: <?php echo ($sourceName && '' !== $sourceName) ? "'$sourceName'" : 'null'; ?>,
        organisation: <?php echo ($organisationId && '' !== $organisationId) ? "'$organisationId'" : 'null'; ?>,
        organisationName: <?php echo ($organisationName && '' !== $organisationName) ? "'$organisationName'" : 'null'; ?>,
        autoupload: <?php echo ("on" === $autoUploadDefault) ? 'true' : 'false'; ?>,
        hasCredentials: <?php echo $hasCredentials ? 'true' : 'false'; ?>,
        identityPoolId: '<?php echo TheContentExchangeSessionService::THE_CONTENT_EXCHANGE_AWS_IDENTITY_POOL; ?>',
        region: '<?php echo TheContentExchangeSessionService::THE_CONTENT_EXCHANGE_AWS_REGION; ?>',
        userPoolId: '<?php echo TheContentExchangeSessionService::THE_CONTENT_EXCHANGE_AWS_USER_POOL; ?>',
        userPoolWebClientId: '<?php echo TheContentExchangeSessionService::THE_CONTENT_EXCHANGE_AWS_USER_POOL_WEBCLIENT; ?>',
        tceApiEndpoint: '<?php echo TheContentExchangeSessionService::THE_CONTENT_EXCHANGE_API_ENDPOINT; ?>',
        copyrightHolder: <?php echo $attachmentCopyrightHolder ? "'$attachmentCopyrightHolder'" : "''"; ?>,
        copyrightUsage: <?php echo $attachmentCopyrightUsage ? "'$attachmentCopyrightUsage'" : "'included'"; ?>,
        pluginDirUrl: "<?php echo $pluginDirUrl; ?>",
    };
    //]]>
</script>
<div class="wrap">
    <!-- Admin notices from this page will displayed after the first header element -->
    <div style="background-color: #013a47; padding: 8px">
        <div class="tce-logo">&nbsp;</div>
    </div>
    <div class="tce-sharing-card tce-sharing-configuration">
        <div class="tce-sharing-card-header">
            <h1>Configure TCE Sharing Wordpress Plugin</h1>
        </div>
        <div class="tce-sharing-card-body">
            <form id="TceConfigurationForm" method="post"
                  action="<?php echo esc_url(admin_url('admin-post.php')) ?>">
                <input type="hidden" name="action"
                       value="tce_sharing_store_configuration">
                <div id="react"><span class="spinner">&nbsp;</span>Loading...
                </div>
                <p class="tce-sharing-configuration-error">
                </p>
            </form>
        </div>
    </div>
</div>

<?php
$editPostsUrl  = esc_url($this->wpUrlService->tceGetWpAdminEditPostsUrl());
$isAdmin       = $this->wpUserService->tceCheckUserRight('manage_options');
$sourceName    = esc_attr($this->configurationService->tceGetSourceName());
$organisation  = esc_attr($this->configurationService->tceGetOrganisationName());
$pluginVersion = esc_attr(THE_CONTENT_EXCHANGE_PLUGIN_VERSION);
$defaultCopyrightValue = esc_attr($this->configurationService->tceGetDefaultAttachmentCopyright());
$defaultCopyrightUsageValue = esc_attr($this->configurationService->tceGetCopyrightUsage());
?>
<div class="tce-sharing-card">
    <div class="tce-sharing-card-header">
        <h1>TCE Sharing Wordpress Plugin</h1>
    </div>
    <div class="tce-sharing-card-body">
        <p>
            Plugin version: <?php echo $pluginVersion; ?>
        </p>
        <p>
            The plugin is connected to the TCE platform. You can now upload posts using the bulk upload
            function in the admin posts page.
        </p>
        <p>
            Content which is shared via this plugin is uploaded to TCE and linked to:<br/>
            <br/>
            <span class="display_configuration_label">Organisation</span>: <b><?php echo $organisation; ?></b><br/>
            <span class="display_configuration_label">Source</span>: <b><?php echo $sourceName; ?></b><br/>
            <span class="display_configuration_label">Copyright holder</span>: <b><?php echo $defaultCopyrightValue; ?></b><br/>
            <span class="display_configuration_label">Copyright usage</span>: <span class="display_configuration_multiline">
                <b>
                    <?php
                    switch ($defaultCopyrightUsageValue) {
                        case 'included':
                            echo "The attached images are included in the content item license.";
                            break;
                        case 'licensed':
                            echo "The attached images are not included, and require a separate license for publication.";
                            break;
                    }
                    ?>
                </b>
                <br/>
                <i>
                    <?php
                    switch ($defaultCopyrightUsageValue) {
                        case 'included':
                            echo "You have the redistribution rights of these images and you allow the buyer of the
                                 content item to redistribute the attached images together with the content item.";
                            break;
                        case 'licensed':
                            echo "You don't have the redistribution rights of these images. The buyer needs to obtain a 
                                 license from the copyright holder directly (i.e. Getty Images / Adobe Stock / etc).";
                            break;
                    }
                    ?>
                </i>
            </span>
            <br/>
        </p>
        <p>
            <a class="tce-sharing-connect-button button button-hero button-primary" href="<?php echo $editPostsUrl; ?>">
                Start Uploading
            </a>
        </p>
    </div>
</div>

<?php if ($isAdmin) : ?>
    <div class="tce-sharing-card">
        <div class="tce-sharing-card-header">
            <h4>Reconfigure TCE Sharing Wordpress Plugin</h4>
        </div>
        <div class="tce-sharing-card-body">
            <p>
                If you want to change the organisation or source, you can do so by reconfiguring the TCE Sharing
                Wordpress Plugin.
                By clicking the button below the existing configuration is reset.
                The button will take you to the TCE Sharing Wordpress Plugin configuration page.<br/>
            </p>
            <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post" id="tce_sharing_disconnect_form">
            <input type="hidden" name="action" value="tce_sharing_disconnect"/>
            <input type="hidden" name="tce_sharing_disconnect_meta_nonce"
                   value="<?php echo wp_create_nonce('tce_sharing_disconnect_meta_form_nonce'); ?>"/>
            <button type="submit" class="tce-sharing-connect-button button button-hero">
                Disconnect / Reconfigure
            </button>
            </form>
        </div>
    </div>
<?php endif; ?>

<?php
$editPostsUrl = esc_url($this->wpUrlService->tceGetWpAdminEditPostsUrl());
?>

<div class="tce-sharing-card">
    <h1>TCE Sharing Help</h1>
    <article>
        <h2>Bulk Uploading</h2>
        <p>
            This plugin allows you to bulk upload files to the TCE platform. This can be done in a few simple steps:
        </p>
        <ol>
            <li>Go to the <a href="<?php echo $editPostsUrl; ?>">Posts</a> page</li>
            <li>Select the posts you want to upload to TCE</li>
            <li>Select the bulk action "Upload to TCE"</li>
            <li>Click the "Apply" button</li>
        </ol>
    </article>

    <article>
        <h2>Upload When Creating or Editing a Post</h2>
        <p>
            This plugin allows you to upload a post to TCE through a setting in the post editor. You will find a
            TCE Sharing area under the "Document" tab of the editor. Here you can select a checkbox "Upload to TCE".
            When you save or update the post, this post will be uploaded to TCE when the checkbox was checked.
            The WordPress administrator can also set this option by default.
        </p>
    </article>
</div>
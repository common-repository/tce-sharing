<?php // Variables are created in TheContentExchangeGutenbergComponent->tceRenderComponent() ?>
<div id="AutoUploadPostForm">
    <input type="checkbox" name="autoUploadPost" id="AutoUploadPost"
        <?php if ("on" === $autoUploadPost) {
            echo 'checked';
        } ?>/>
    <label for="AutoUploadPost">Auto upload post to TCE</label>
</div>


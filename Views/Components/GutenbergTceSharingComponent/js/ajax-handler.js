jQuery(document).ready(function ($) {

    "use strict";

    $("#AutoUploadPost").change(function (event) {

        event.preventDefault(); // Prevent the default form submit.

        // serialize the form data
        let ajaxFormData = $("#AutoUploadPost").serializeArray();
        const formData = {};
        ajaxFormData.map((dataSet) => {
            formData[dataSet.name] = dataSet.value;
        });

        // If a checkbox is unchecked, the value is skipped in jquery's serializeArray.
        let uncheckedBoxes = $("#AutoUploadPostForm input[type=checkbox]:not(:checked)");
        $.each(uncheckedBoxes, (index, input) => {
            formData[input.name] = input.checked ? "on" : "off";
        });

        $.ajax({
            url: params.wpAjaxUrl, // domain/wp-admin/admin-ajax.php
            type: "post",
            data: {
                action: "tce_sharing_store_auto_upload_post",
                postId: params.postId,
                ...formData,
            }
        });
    });
});

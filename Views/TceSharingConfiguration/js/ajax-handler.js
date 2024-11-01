jQuery(document).ready(function($) {

    "use strict";

    $("#TceConfigurationForm").submit(function(event) {

        event.preventDefault(); // Prevent the default form submit.

        // serialize the form data
        let ajaxFormData = $("#TceConfigurationForm").serializeArray();
        // Format the data to a clean array with name => value pairs.
        const formData = {};
        ajaxFormData.map((dataSet) => {
            formData[dataSet.name] = dataSet.value;
        });

        // If a checkbox is unchecked, the value is skipped in jquery's serializeArray.
        let uncheckedBoxes = $("#TceConfigurationForm input[type=checkbox]:not(:checked)");
        $.each(uncheckedBoxes, (index, input) => {
            formData[input.name] = input.checked ? "on" : "off";
        });

        $.ajax({
            url: params.wpAjaxUrl, // domain/wp-admin/admin-ajax.php
            type: "post",
            data: {
                ajaxRequest: true,
                submit: "Submit Form",
                ...formData
            }
        })
        .done(function(response) {
            window.location.replace(response + "&configuration-status=updated");
        })
        // something went wrong
        .fail(function(response) {
            window.location.replace(response.responseText + "&configuration-status=failed");
        });
    });
});

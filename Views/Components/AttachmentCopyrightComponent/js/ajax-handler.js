jQuery(document).ready(function ( $ ) {

    "use strict";

    if (wp.media) {
        // Attachment Modal.
        const AttachmentCompat = wp.media.view.AttachmentCompat;

        wp.media.view.AttachmentCompat = AttachmentCompat.extend({
            render: function () {
                const compat = this.model.get('compat');
                if (!compat || !compat.item) {
                    return;
                }

                this.views.detach();
                this.$el.html(compat.item);
                addCopyrightEventListener(this.$el);
                this.views.render();

                return this;
            }
        })
    } else {
        addCopyrightEventListener($(document));
    }

    /**
     * Search for the TCE Copyright Usage radio buttons inside an element and add an eventListener
     * to decide whether the Copyright Holder field has to be replaced by the organisation name.
     *
     * @param rootElement
     */
    function addCopyrightEventListener(rootElement)
    {
        const radioButtons = rootElement?.find('label[for^="attachments-tce_copyright_usage"] input[type="radio"]');
        if (radioButtons) {
            radioButtons.map(function () {
                this.addEventListener('change', (event) => {
                    if ('included' === event.target.value && event.target.checked) { // TODO .checked is overrated??
                        const input = $('.compat-field-tce_copyright_information').find('[id$="tce_copyright_information"]')[0];
                        input.value = params.organisationName;
                    }
                });
            });
        }
    }
});

jQuery(document).ready(function ($) {

    "use strict";

    if (wp.media) {
        const AttachmentsBrowser = wp.media.view.AttachmentsBrowser;
        const SelectModeToggleButton = wp.media.view.SelectModeToggleButton;
        const Button = wp.media.view.Button;
        const buttonClass = "edit-copyright-selected-button";
        const svgpainter = wp.svgPainter;
        const colors = {
            ...window._wpColorScheme,
            icons: {
                ..._wpColorScheme.icons,
                tce: '#ffffff',
                tce_disabled: '#a0a5aa'
            }
        }

        // Create a new button
        const DefaultCopyrightButton = Button.extend({
            initialize: function () {
                Button.prototype.initialize.apply(this, arguments);
                this.controller.on("selection:toggle", this.toggleDisabled, this);
                this.controller.on("select:activate", this.toggleDisabled, this);
            },

            toggleDisabled: function () {
                const disabled = !this.controller.state().get("selection").length;
                svgpainter.paintElement(this.$el, (disabled ? 'tce_disabled' : 'tce'));
                this.model.set("disabled", disabled);
            },

            render: function () {
                Button.prototype.render.apply(this, arguments);
                if (this.controller.isModeActive("select")) {
                    this.$el.addClass(buttonClass);
                } else {
                    this.$el.addClass(buttonClass + " hidden");
                }
                this.toggleDisabled();
                return this;
            }
        });

        wp.media.view.AttachmentsBrowser = AttachmentsBrowser.extend({
            createToolbar: function () {
                "use strict";

                // Run the standard createToolbar function first.
                AttachmentsBrowser.prototype.createToolbar.call(this);
                // Add the button to the toolbar.
                if (this.controller.isModeActive("grid")) {
                    this.toolbar.set("bulkEditAttachmentCopyright", new DefaultCopyrightButton({
                        style: "tce",
                        disabled: false,
                        text: "Add default copyright",
                        controller: this.controller,
                        priority: -100,
                        click: function () {
                            const changed = [];
                            const controller = this.controller;
                            const selection = controller.state().get("selection");
                            const library = controller.state().get("library");

                            // Do nothing if no attachment is selected.
                            if (!selection.length) {
                                return;
                            }

                            selection.each(function (model) {

                                const data = [];
                                data[`attachments[${model.id}][tce_copyright_usage]`] = bulkParams.copyrightUsageValue;
                                data[`attachments[${model.id}][tce_copyright_information]`] = bulkParams.copyrightInformationValue;

                                controller.trigger('attachment:compat:waiting', ['waiting']);
                                model.saveCompat(data, {}).then(
                                    function (model) {
                                        controller.trigger('attachment:compat:ready', ['ready']);
                                        changed.push(model);
                                        // If all attachments are changed, stop the selection.
                                        if (changed.length === selection.length) {
                                            controller.trigger('selection:action:done');
                                            // Refresh the library.
                                            library._requery(true);
                                        }
                                    },
                                    function (reason) {
                                        console.log("error", reason);
                                    }
                                );
                            });
                        }
                    }).render());
                }
            }
        });

        // This extension will determinate the state of the copyright button when there are attachments selected.
        wp.media.view.SelectModeToggleButton = SelectModeToggleButton.extend({
            toggleBulkEditHandler: function () {
                "use strict";

                // Run the standard function first.
                SelectModeToggleButton.prototype.toggleBulkEditHandler.call(this);

                const toolbar = this.controller.content.get().toolbar;
                const button = toolbar.$("." + buttonClass);
                svgpainter.init();
                svgpainter.setColors(colors);

                if (this.controller.isModeActive("select")) {
                    button.removeClass("hidden");
                    svgpainter.paintElement(button, 'tce');
                } else {
                    button.addClass("hidden");
                    svgpainter.paintElement(button, 'tce_disabled');
                    this.controller.state().get("selection").reset();
                }
            }
        });
    }
});




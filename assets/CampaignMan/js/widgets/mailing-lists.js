/**
 * Set up the form in a modal after being successfully attached to the body.
 */
function attachMLForm() {
    $("body").on('renderSuccess.ufModal', function(data) {
        var modal = $(this).ufModal('getModal');
        var form = modal.find('.js-form');

        /**
         * Set up modal widgets
         */
        // Set up any widgets inside the modal
        form.find(".js-select2").select2({
            width: '100%'
        });

        // Set up the form for submission
        form.ufForm({
            validator: page.validators
        }).on("submitSuccess.ufForm", function() {
            // Reload page on success
            window.location.reload();
        });
    });
}

/**
 * Link group action buttons, for example in a table or on a specific mailing list's page.
 * @param {module:jQuery} el jQuery wrapped element to target.
 * @param {{delete_redirect: string}} options Options used to modify behaviour of button actions.
 */
function bindMLButtons(el, options) {
    if (!options) options = {};

    /**
     * Link row buttons after table is loaded.
     */

    /**
     * Buttons that launch a modal dialog
     */
    // Edit mailing list details button
    el.find('.js-ml-edit').click(function(e) {
        e.preventDefault();

        $("body").ufModal({
            sourceUrl: site.uri.public + "/modals/mailing_lists/edit",
            ajaxParams: {
                ml_id: $(this).data('id')
            },
            msgTarget: $("#alerts-page")
        });

        attachMLForm();
    });

    // Delete mailing list button
    el.find('.js-ml-delete').click(function(e) {
        e.preventDefault();

        $("body").ufModal({
            sourceUrl: site.uri.public + "/modals/mailing_lists/confirm-delete",
            ajaxParams: {
                ml_id: $(this).data('id')
            },
            msgTarget: $("#alerts-page")
        });

        $("body").on('renderSuccess.ufModal', function() {
            var modal = $(this).ufModal('getModal');
            var form = modal.find('.js-form');

            form.ufForm()
                .on("submitSuccess.ufForm", function() {
                    // Navigate or reload page on success
                    if (options.delete_redirect) window.location.href = options.delete_redirect;
                    else window.location.reload();
                });
        });
    });
}

function bindMLCreationButton(el) {
    // Link create button
    el.find('.js-ml-create').click(function(e) {
        e.preventDefault();

        $("body").ufModal({
            sourceUrl: site.uri.public + "/modals/mailing_lists/create",
            msgTarget: $("#alerts-page")
        });

        attachMLForm();
    });
};
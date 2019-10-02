$(document).ready(function() {

    $("#widget-mailing-lists").ufTable({
        dataUrl: `${site.uri.public}/api/${group ? `group/${group.slug}/` : ""}mailing_lists`,
        useLoadingTransition: site.uf_table.use_loading_transition
    });

    // Bind creation button
    bindMLCreationButton($("#widget-mailing-lists"));

    // Bind table buttons
    $("#widget-mailing-lists").on("pagerComplete.ufTable", function () {
        bindMLButtons($(this));
    });
});
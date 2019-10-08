$(document).ready(function() {

    $("#widget-mailing-lists").ufTable({
        dataUrl: `${site.uri.public}/api/mailing_queue`,
        useLoadingTransition: site.uf_table.use_loading_transition
    });

    // Bind creation button
    bindMQClearButton($("#widget-mailing-queue"));

    // Bind table buttons
    $("#widget-mailing-queue").on("pagerComplete.ufTable", function () {
        bindMQButtons($(this));
    });
});
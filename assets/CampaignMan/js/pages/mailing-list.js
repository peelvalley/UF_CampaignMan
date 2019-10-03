$(document).ready(function() {

    $("#widget-subscribers").ufTable({
        dataUrl: `${site.uri.public}/api/mailing_lists/ml/${mailing_list.id}/subscribers`,
        useLoadingTransition: site.uf_table.use_loading_transition
    });

    // Bind creation button
    bindSubscriberCreationButton($("#widget-subscribers"));

    // Bind table buttons
    $("#widget-subscriber").on("pagerComplete.ufTable", function () {
        bindSubscriberButtons($(this));
    });
});
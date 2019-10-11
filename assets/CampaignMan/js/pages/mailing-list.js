$(document).ready(function() {

    $("#widget-subscriptions").ufTable({
        dataUrl: `${site.uri.public}/api/mailing_lists/ml/${mailing_list.id}/subscriptions`,
        useLoadingTransition: site.uf_table.use_loading_transition
    });

    // Bind creation button
    bindSubscriberCreationButton($("#widget-subscriptions"));

    // Bind table buttons
    $("#widget-subscriber").on("pagerComplete.ufTable", function () {
        bindSubscriberButtons($(this));
    });
});
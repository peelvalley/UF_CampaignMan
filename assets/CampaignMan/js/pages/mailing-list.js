$(document).ready(function() {

    $("#widget-subscriptions").ufTable({
        dataUrl: `${site.uri.public}/api/mailing_lists/ml/${mailing_list.id}/subscriptions`,
        useLoadingTransition: site.uf_table.use_loading_transition
    });

    // Bind creation button
    bindSubscriptionCreationButton($("#widget-subscriptions"));

    // Bind table buttons
    $("#widget-subscriptions").on("pagerComplete.ufTable", function () {
        bindSubscriptionButtons($(this));
    });
});
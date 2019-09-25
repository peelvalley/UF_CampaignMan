<?php

use UserFrosting\Sprinkle\Core\Util\NoCache;


$app->group('/mailing_lists', function () {
    $this->get('', 'UserFrosting\Sprinkle\CampaignMan\Controller\MailingListController:pageList')
    ->setName('uri_events');

    $this->get('/create', 'UserFrosting\Sprinkle\CampaignMan\Controller\MailingListController:pageCreate');

    $this->get('/ml/{ml_id}', 'UserFrosting\Sprinkle\CampaignMan\Controller\MailingListController:pageInfo');

})->add('authGuard')->add(new NoCache());



$app->group('/api/events', function () {
    $this->get('', 'UserFrosting\Sprinkle\CampaignMan\Controller\MailingListController:getList');

    $this->post('', 'UserFrosting\Sprinkle\CampaignMan\Controller\MailingListController:create');

    $this->delete('/ml/{ml_id}', 'UserFrosting\Sprinkle\CampaignMan\Controller\MailingListController:delete');

    $this->get('/ml/{ml_id}', 'UserFrosting\Sprinkle\CampaignMan\Controller\MailingListController:getInfo');

    $this->put('/ml/{ml_id}', 'UserFrosting\Sprinkle\CampaignMan\Controller\MailingListController:updateInfo');

    $this->put('/ml/{ml_id}/{field}', 'UserFrosting\Sprinkle\CampaignMan\Controller\MailingListController:updateField');


})->add('authGuard')->add(new NoCache());

$app->group('/modals/events', function () {
    $this->get('/confirm-delete', 'UserFrosting\Sprinkle\CampaignMan\Controller\MailingListController:getModalConfirmDelete');
})->add('authGuard')->add(new NoCache());

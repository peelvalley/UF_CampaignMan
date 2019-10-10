<?php

use UserFrosting\Sprinkle\Core\Util\NoCache;


$app->group('/groups', function () {
    $this->get('/g/{slug}/mailing_lists', 'UserFrosting\Sprinkle\CampaignMan\Controller\GroupMailingController:pageMailingLists')
    ->setName('uri_group_mailing_lists');
})->add('authGuard')->add(new NoCache());

$app->group('/api/groups', function () {
    $this->get('/g/{slug}/mailing_lists', 'UserFrosting\Sprinkle\CampaignMan\Controller\GroupMailingController:getMailingLists');
    $this->post('/g/{slug}/mailing_lists/ml/{ml_slug}', 'UserFrosting\Sprinkle\CampaignMan\Controller\GroupMailingController:createSubscriber');
})->add('authGuard')->add(new NoCache());

$app->group('/modals/groups', function () {
    $this->get('/g/{slug}/mailing_lists/ml/{ml_slug}/subscribe', 'UserFrosting\Sprinkle\CampaignMan\Controller\GroupMailingController:getModalCreateSubscription');
})->add('authGuard')->add(new NoCache());
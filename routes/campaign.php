<?php

use UserFrosting\Sprinkle\Core\Util\NoCache;


$app->group('/campaigns', function () {
    $this->get('', 'UserFrosting\Sprinkle\CampaignMan\Controller\CampaignController:pageList')
    ->setName('uri_campaigns');

    $this->get('/create', 'UserFrosting\Sprinkle\CampaignMan\Controller\CampaignController:pageCreate');

    $this->get('/c/{campaign_id}', 'UserFrosting\Sprinkle\CampaignMan\Controller\CampaignController:pageInfo');

})->add('authGuard')->add(new NoCache());

$app->group('/api/campaigns', function () {


    $this->get('/c/{campaign_id}/subscriptions', 'UserFrosting\Sprinkle\CampaignMan\Controller\CampaignController:getSubscriptions');



})->add('authGuard')->add(new NoCache());
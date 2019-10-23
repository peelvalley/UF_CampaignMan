<?php

use UserFrosting\Sprinkle\Core\Util\NoCache;


$app->group('/campaigns', function () {
    $this->get('', 'UserFrosting\Sprinkle\Ems\Controller\CampaignController:pageList')
    ->setName('uri_campaigns');

    $this->get('/create', 'UserFrosting\Sprinkle\Ems\Controller\CampaignController:pageCreate');

    $this->get('/c/{campaign_id}', 'UserFrosting\Sprinkle\Ems\Controller\CampaignController:pageInfo');

})->add('authGuard')->add(new NoCache());

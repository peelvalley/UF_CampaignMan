<?php

use UserFrosting\Sprinkle\Core\Util\NoCache;



$app->group('/api/subscribers', function () {
    $this->post('', 'UserFrosting\Sprinkle\CampaignMan\Controller\SubscriberController:create');

    $this->delete('/s/{subscriber_id}', 'UserFrosting\Sprinkle\CampaignMan\Controller\SubscriberController:delete');

    $this->get('/s/{subscriber_id}', 'UserFrosting\Sprinkle\CampaignMan\Controller\SubscriberController:getInfo');

    $this->put('/s/{subscriber_id}', 'UserFrosting\Sprinkle\CampaignMan\Controller\SubscriberController:updateInfo');

    $this->put('/s/{subscriber_id}/{field}', 'UserFrosting\Sprinkle\CampaignMan\Controller\SubscriberController:updateField');


})->add('authGuard')->add(new NoCache());

$app->group('/modals/subscribers', function () {
    $this->get('/create', 'UserFrosting\Sprinkle\CampaignMan\Controller\SubscriberController:getModalCreate');
    $this->get('/confirm-delete', 'UserFrosting\Sprinkle\CampaignMan\Controller\SubscriberController:getModalConfirmDelete');
})->add('authGuard')->add(new NoCache());

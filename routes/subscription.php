<?php

use UserFrosting\Sprinkle\Core\Util\NoCache;



$app->group('/api/subscriptions', function () {
    $this->post('', 'UserFrosting\Sprinkle\CampaignMan\Controller\SubscriptionController:create');

    $this->delete('/s/{subscription_id}', 'UserFrosting\Sprinkle\CampaignMan\Controller\SubscriptionController:delete');

    $this->get('/s/{subscription_id}', 'UserFrosting\Sprinkle\CampaignMan\Controller\SubscriptionController:getInfo');

    $this->put('/s/{subscription_id}', 'UserFrosting\Sprinkle\CampaignMan\Controller\SubscriptionController:updateInfo');

    $this->put('/s/{subscription_id}/{field}', 'UserFrosting\Sprinkle\CampaignMan\Controller\SubscriptionController:updateField');

})->add('authGuard')->add(new NoCache());

$app->group('/modals/subscriptions', function () {
    $this->get('/edit', 'UserFrosting\Sprinkle\CampaignMan\Controller\SubscriptionController:getModalEdit');
    $this->get('/confirm-unsubscribe', 'UserFrosting\Sprinkle\CampaignMan\Controller\SubscriptionController:getModalConfirmUnsubscribe');
})->add('authGuard')->add(new NoCache());

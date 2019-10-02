<?php

use UserFrosting\Sprinkle\Core\Util\NoCache;


$app->group('/groups', function () {
    $this->get('/g/{slug}/mailing_lists', 'UserFrosting\Sprinkle\CampaignMan\Controller\GroupMailingController:pageMailingLists')
    ->setName('uri_group_mailing_lists');


})->add('authGuard')->add(new NoCache());
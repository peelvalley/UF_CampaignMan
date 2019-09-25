<?php

namespace UserFrosting\Sprinkle\CampaignMan\ServicesProvider;

use UserFrosting\Sprinkle\Ems\Twig\EmsExtension;

/**
 * Registers services for the pis sprinkle.
 *
 */
class ServicesProvider
{
    /**
     *
     * @param Container $container A DI container implementing ArrayAccess and container-interop.
     */
    public function register($container)
    {
        $container->extend('classMapper', function ($classMapper, $c) {
            $classMapper->setClassMapping('campaign', 'UserFrosting\Sprinkle\CampaignMan\Database\Models\Campaign');
            $classMapper->setClassMapping('campaign_sub', 'UserFrosting\Sprinkle\CampaignMan\Database\Models\CampaignSub');
            $classMapper->setClassMapping('list_sub', 'UserFrosting\Sprinkle\CampaignMan\Database\Models\ListSub');
            $classMapper->setClassMapping('mailing_list', 'UserFrosting\Sprinkle\CampaignMan\Database\Models\MailingList');
            $classMapper->setClassMapping('subscriber', 'UserFrosting\Sprinkle\CampaignMan\Database\Models\Subscriber');
            $classMapper->setClassMapping('sub_verification', 'UserFrosting\Sprinkle\CampaignMan\Database\Models\SubscriberVerification');
            return $classMapper;
        });
    }
}
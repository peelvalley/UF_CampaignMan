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
            $classMapper->setClassMapping('group', 'UserFrosting\Sprinkle\CampaignMan\Database\Models\Group');
            $classMapper->setClassMapping('campaign', 'UserFrosting\Sprinkle\CampaignMan\Database\Models\Campaign');
            $classMapper->setClassMapping('campaign_sub', 'UserFrosting\Sprinkle\CampaignMan\Database\Models\CampaignSub');
            $classMapper->setClassMapping('subscription', 'UserFrosting\Sprinkle\CampaignMan\Database\Models\Subscription');
            $classMapper->setClassMapping('mailing_list', 'UserFrosting\Sprinkle\CampaignMan\Database\Models\MailingList');
            $classMapper->setClassMapping('subscriber', 'UserFrosting\Sprinkle\CampaignMan\Database\Models\Subscriber');
            $classMapper->setClassMapping('sub_verification', 'UserFrosting\Sprinkle\CampaignMan\Database\Models\SubscriberVerification');
            $classMapper->setClassMapping('mailing_queue', 'UserFrosting\Sprinkle\CampaignMan\Database\Models\MailingQueue');
            $classMapper->setClassMapping('subscriber_sprunje', 'UserFrosting\Sprinkle\CampaignMan\Sprunje\SubscriberSprunje');
            $classMapper->setClassMapping('subscription_sprunje', 'UserFrosting\Sprinkle\CampaignMan\Sprunje\SubscriptionSprunje');
            $classMapper->setClassMapping('mailing_list_sprunje', 'UserFrosting\Sprinkle\CampaignMan\Sprunje\MailingListSprunje');
            $classMapper->setClassMapping('mailing_queue_sprunje', 'UserFrosting\Sprinkle\CampaignMan\Sprunje\MailingQueueSprunje');

            return $classMapper;
        });
    }
}
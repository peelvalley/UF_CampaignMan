<?php

namespace UserFrosting\Sprinkle\CampaignMan\Database\Seeds;

use UserFrosting\Sprinkle\Core\Database\Seeder\BaseSeed;
use UserFrosting\Sprinkle\CampaignMan\Database\Models\Group;
use UserFrosting\Sprinkle\CampaignMan\Database\Models\MailingList;

use UserFrosting\Sprinkle\Core\Facades\Seeder;


class DefaultMailingLists extends BaseSeed
{
    /**
     * {@inheritDoc}
     */
    public function run()
    {
       foreach (Group::doesnthave('mailingLists')->get() as $group) {
            $group->mailingLists()->save( new MailingList([
                'description' => 'Default mailing list'
            ]));
       }
    }
}
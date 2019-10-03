<?php


namespace UserFrosting\Sprinkle\CampaignMan\Sprunje;

use Illuminate\Database\Schema\Builder;
use UserFrosting\Sprinkle\Core\Facades\Translator;
use UserFrosting\Sprinkle\Core\Sprunje\Sprunje;

/**
 * MailingListSprunje.
 *
 * Implements Sprunje for the mailing list API.
 *
 * @author PeelValley Software (https://peelvalley.com.au)
 */
class SubscriberSprunje extends Sprunje
{
    protected $name = 'mailing_lists';

    protected $listable = [];

    protected $sortable = [
        'email'
    ];

    protected $filterable = [
        'email'
    ];

    /**
     * {@inheritdoc}
     */
    protected function baseQuery()
    {
        return $this->$classMapper->getClassMapping('subscriber');
    }
}
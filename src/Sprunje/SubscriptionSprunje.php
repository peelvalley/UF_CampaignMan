<?php


namespace UserFrosting\Sprinkle\CampaignMan\Sprunje;

use Illuminate\Database\Schema\Builder;
use UserFrosting\Sprinkle\Core\Facades\Translator;
use UserFrosting\Sprinkle\Core\Sprunje\Sprunje;

/**
 * SubscriberSprunje.
 *
 * Implements Sprunje for the subscrber API.
 *
 * @author PeelValley Software (https://peelvalley.com.au)
 */
class SubscriptionSprunje extends Sprunje
{
    protected $name = 'subscribers';

    protected $listable = [
        'email'
    ];

    protected $sortable = [
        'subscriber_name',
        'email',
        'group'
    ];

    protected $filterable = [
        'subscriber_name',
        'email',
        'group'
    ];

    /**
     * {@inheritdoc}
     */
    protected function baseQuery()
    {
        $query = $this->classMapper->getClassMapping('subscription');
        return $query;
    }
}
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
class CampaignSubSprunje extends Sprunje
{
    protected $name = 'campaign_subscriber';

    protected $listable = [];

    protected $sortable = [
        'subscriber_name'
    ];

    protected $filterable = [
        'subscriber_name'
    ];

    /**
     * {@inheritdoc}
     */
    protected function baseQuery()
    {
        $query = $this->classMapper->getClassMapping('campaign_sub');
        return $query;
    }

    protected function filterGroupName($query, $value)
    {
        // Split value on separator for OR queries
        $values = explode($this->orSeparator, $value);
        $query->whereHas('group', function($q){
            $q->where(function ($query) use ($values) {
                foreach ($values as $value) {
                    $query->orLike('name', $value);
                }
            });
        });
        return $this;
    }

    /**
     * Sort based on last name.
     *
     * @param Builder $query
     * @param string  $direction
     *
     * @return self
     */
    protected function sortName($query, $direction)
    {
        $query->orderBy('last_name', $direction);
        return $this;
    }
}
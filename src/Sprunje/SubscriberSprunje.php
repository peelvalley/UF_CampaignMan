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
class SubscriberSprunje extends Sprunje
{
    protected $name = 'subscribers';

    protected $listable = [
        'email'
    ];

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
        throw new \Exception(print_r([$this], TRUE));
        return $this->$classMapper->getClassMapping('subscriber');
    }
}
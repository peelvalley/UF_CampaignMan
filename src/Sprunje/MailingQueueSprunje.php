<?php


namespace UserFrosting\Sprinkle\CampaignMan\Sprunje;

use Illuminate\Database\Schema\Builder;
use UserFrosting\Sprinkle\Core\Facades\Translator;
use UserFrosting\Sprinkle\Core\Sprunje\Sprunje;

/**
 * MailingQueueSprunje.
 *
 * Implements Sprunje for the mailing queue API.
 *
 * @author PeelValley Software (https://peelvalley.com.au)
 */
class MailingQueueSprunje extends Sprunje
{
    protected $name = 'mailing_queue';

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
        return $this->classMapper->createInstance('mailing_queue');
    }
}
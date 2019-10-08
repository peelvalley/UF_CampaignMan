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

    protected $appends = [
        'email'
    ];


    protected function listEmail() {
        return $this->classMapper->createInstance('mailing_queue')->all('to')->map(function($to) {
            return [
                'value' => $to[0],
                'text' => $to[1],
            ];
        });
    }

    /**
     * {@inheritdoc}
     */
    protected function baseQuery()
    {
        return $this->classMapper->createInstance('mailing_queue');
    }



}


function map(callable $fn)
{
    $result = array();

    foreach ($this as $item) {
        $result[] = $fn($item);
    }

    return $result;
}
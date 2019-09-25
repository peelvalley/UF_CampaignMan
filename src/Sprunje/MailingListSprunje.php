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
class MailingListSprunje extends Sprunje
{
    protected $name = 'mailing_lists';

    protected $listable = [];

    protected $sortable = [
        'description',
        'group_name'
    ];

    protected $filterable = [
        'group_name'
    ];

    /**
     * {@inheritdoc}
     */
    protected function baseQuery()
    {
        return $this->classMapper->createInstance('mailing_list');
    }
}
<?php


namespace UserFrosting\Sprinkle\CampaignMan\Database\Models;

use Illuminate\Database\Eloquent\Builder;
use UserFrosting\Sprinkle\Core\Database\Models\Model;


class ListSub extends Model
{
    /**
     * @var string The name of the table for the current model.
     */
    protected $table = 'list_subs';

    protected $fillable = [
        'list_id',
        'subscriber_id',
        'enabled'
    ];

    /**
     * @var bool Enable timestamps for this class.
     */
    public $timestamps = true;

    protected $dates = [];

    protected $casts = [];

    public function mailingList()
    {
        /** @var \UserFrosting\Sprinkle\Core\Util\ClassMapper $classMapper */
        $classMapper = static::$ci->classMapper;

        return $this->belongsTo($classMapper->getClassMapping('mailing_list'));
    }


    public function subscriber()
    {
        /** @var \UserFrosting\Sprinkle\Core\Util\ClassMapper $classMapper */
        $classMapper = static::$ci->classMapper;

        return $this->belongsTo($classMapper->getClassMapping('subscriber'));
    }
}
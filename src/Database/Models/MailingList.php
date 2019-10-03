<?php


namespace UserFrosting\Sprinkle\CampaignMan\Database\Models;

use Illuminate\Database\Eloquent\Builder;
use UserFrosting\Sprinkle\Core\Database\Models\Model;
use Illuminate\Database\Eloquent\SoftDeletes;



class MailingList extends Model
{
    use SoftDeletes;
    /**
     * @var string The name of the table for the current model.
     */
    protected $table = 'mailing_lists';

    protected $fillable = [
        'group_id',
        'name',
        'slug',
        'description',
        'metadata'
    ];


    /**
     * @var bool Enable timestamps for this class.
     */
    public $timestamps = true;

    protected $dates = [];

    protected $casts = [
        'metadata' => 'array'
    ];

    public function subscribers()
    {
        /** @var \UserFrosting\Sprinkle\Core\Util\ClassMapper $classMapper */
        $classMapper = static::$ci->classMapper;

        return $this->belongsToManyThrough($classMapper->getClassMapping('subscriber'), $classMapper->getClassMapping('subscription'), 'subscriber_subscriptions');
    }

    public function group()
    {
        /** @var \UserFrosting\Sprinkle\Core\Util\ClassMapper $classMapper */
        $classMapper = static::$ci->classMapper;

        return $this->belongsTo($classMapper->getClassMapping('group'), 'group_id');
    }
}
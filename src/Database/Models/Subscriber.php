<?php


namespace UserFrosting\Sprinkle\CampaignMan\Database\Models;

use Illuminate\Database\Eloquent\Builder;
use UserFrosting\Sprinkle\Core\Database\Models\Model;
use Illuminate\Database\Eloquent\SoftDeletes;



class Subscriber extends Model
{
    use SoftDeletes;
    /**
     * @var string The name of the table for the current model.
     */
    protected $table = 'subscribers';

    protected $fillable = [
        'email',
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

    public function mailingLists()
    {
        /** @var \UserFrosting\Sprinkle\Core\Util\ClassMapper $classMapper */
        $classMapper = static::$ci->classMapper;

        return $this->belongsToMany($classMapper->getClassMapping('mailing_list'), $classMapper->getClassMapping('subscription'));
    }


    public function verifications()
    {
        /** @var \UserFrosting\Sprinkle\Core\Util\ClassMapper $classMapper */
        $classMapper = static::$ci->classMapper;

        return $this->hasMany($classMapper->getClassMapping('subscriber_verification'), 'subscriber_id');
    }
}
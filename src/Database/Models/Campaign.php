<?php


namespace UserFrosting\Sprinkle\CampaignMan\Database\Models;

use Illuminate\Database\Eloquent\Builder;
use UserFrosting\Sprinkle\Core\Database\Models\Model;
use Illuminate\Database\Eloquent\SoftDeletes;



class Campaign extends Model
{
    use SoftDeletes;
    /**
     * @var string The name of the table for the current model.
     */
    protected $table = 'campaigns';

    protected $fillable = [
        'status',
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

        return $this->belongsToMany($classMapper->getClassMapping('subscriber'), $classMapper->getClassMapping('campaign_sub'));
    }

    public function subscriptions()
    {
        /** @var \UserFrosting\Sprinkle\Core\Util\ClassMapper $classMapper */
        $classMapper = static::$ci->classMapper;

        return $this->hasMany($classMapper->getClassMapping('campaign_sub'));
    }

    public function group()
    {
        /** @var \UserFrosting\Sprinkle\Core\Util\ClassMapper $classMapper */
        $classMapper = static::$ci->classMapper;

        return $this->belongsTo($classMapper->getClassMapping('group'), 'group_id');
    }

}
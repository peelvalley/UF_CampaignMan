<?php

namespace UserFrosting\Sprinkle\CampaignMan\Database\Models;

use UserFrosting\Sprinkle\Core\Database\Models\Model;

/**
 * SubscriberVerification Class.
 *
 * Represents a pending email verification for a subscriber.
 *
 * @author PeelValley Software (https://peelvalley.com.au)
 *
 * @property int user_id
 * @property hash token
 * @property bool completed
 * @property datetime expires_at
 * @property datetime completed_at
 */
class SubscriberVerification extends Model
{
    /**
     * @var string The name of the table for the current model.
     */
    protected $table = 'verifications';

    protected $fillable = [
        'subscriber_id',
        'hash',
        'completed',
        'expires_at',
        'completed_at',
    ];

    /**
     * @var bool Enable timestamps for Verifications.
     */
    public $timestamps = true;

    /**
     * @var string Stores the raw (unhashed) token when created, so that it can be emailed out to the subscriber.  NOT persisted.
     */
    protected $token;

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param string $value
     *
     * @return self
     */
    public function setToken($value)
    {
        $this->token = $value;

        return $this;
    }

    /**
     * Get the subscriber associated with this verification request.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function subscriber()
    {
        /** @var \UserFrosting\Sprinkle\Core\Util\ClassMapper $classMapper */
        $classMapper = static::$ci->classMapper;

        return $this->belongsTo($classMapper->getClassMapping('subscriber'), 'subscriber_id');
    }
}
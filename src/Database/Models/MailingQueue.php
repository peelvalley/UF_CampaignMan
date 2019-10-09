<?php


namespace UserFrosting\Sprinkle\CampaignMan\Database\Models;

use Illuminate\Database\Eloquent\Builder;
use UserFrosting\Sprinkle\Core\Database\Models\Model;


class MailingQueue extends Model
{
    /**
     * @var string The name of the table for the current model.
     */
    protected $table = 'mailing_queue';

    protected $fillable = [
        'template',
        'to',
        'from',
        'data',
        'metadata',
        'metadata->error',
        'metadata->status',
        'attachments'
    ];

    /**
     * @var bool Enable timestamps for this class.
     */
    public $timestamps = true;

    protected $dates = [];

    protected $casts = [
        'to' => 'array',
        'from' => 'array',
        'data' => 'array',
        'metadata' => 'array',
        'attachments' => 'array'
    ];

    public function getEmailAttribute() {
        return $this->to[0];
    }
}
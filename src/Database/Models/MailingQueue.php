<?php


namespace UserFrosting\Sprinkle\CampaignMan\Database\Models;

use Illuminate\Database\Eloquent\Builder;
use UserFrosting\Sprinkle\Core\Database\Models\Model;


class MailingQueue extends Model
{
    use SoftDeletes;
    /**
     * @var string The name of the table for the current model.
     */
    protected $table = 'mailing_lists';

    protected $fillable = [
        'template_name',
        'data',
        'metadata',
        'attachments'
    ];

    /**
     * @var bool Enable timestamps for this class.
     */
    public $timestamps = true;

    protected $dates = [];

    protected $casts = [
        'data' => 'array',
        'metadata' => 'array',
        'attachments' => 'array'
    ];
}
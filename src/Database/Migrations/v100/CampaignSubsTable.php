<?php

namespace UserFrosting\Sprinkle\CampaignMan\Database\Migrations\v100;

use Illuminate\Database\Schema\Blueprint;
use UserFrosting\System\Bakery\Migration;

class CampaignsSubsTable extends Migration
{
    public static $dependencies = [
        '\UserFrosting\Sprinkle\CampaignMan\Database\Migrations\v100\SubscribersTable',
        '\UserFrosting\Sprinkle\CampaignMan\Database\Migrations\v100\CampaignsTable'

    ];

    /**
    * {@inheritdoc}
    */
    public function up()
    {
        if (!$this->schema->hasTable('campaign_subs')) {
            $this->schema->create('campaign_subs', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('campaign_id')->unsigned();
                $table->integer('subscriber_id')->unsigned();

                $table->timestamps();

                $table->foreign('campaign_id')->references('id')->on('campaigns');
                $table->foreign('subscriber_id')->references('id')->on('subscribers');

                $table->engine = 'InnoDB';
                $table->collation = 'utf8mb4_unicode_520_ci';
                $table->charset = 'utf8mb4';
            });
        }
    }

    /**
    * {@inheritdoc}
    */
    public function down()
    {
        if ($this->schema->hasTable('campaign_subs')) {
            $this->schema->drop('campaign_subs');
        }
    }
}
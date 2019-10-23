<?php

namespace UserFrosting\Sprinkle\CampaignMan\Database\Migrations\v100;

use Illuminate\Database\Schema\Blueprint;
use UserFrosting\Sprinkle\Core\Database\Migration;

class CampaignSubsTable extends Migration
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
        if (!$this->schema->hasTable('campaign_subscriber')) {
            $this->schema->create('campaign_subscriber', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('campaign_id')->unsigned();
                $table->integer('subscriber_subscription_id')->unsigned();
                $table->string('status', 15)->default('pending');
                $table->boolean('enabled')->default(TRUE);
                $table->json('data')->nullable();

                $table->timestamps();

                $table->index('status');
                $table->index('enabled');

                $table->foreign('campaign_id')->references('id')->on('campaigns')->onDelete('cascade')->onUpdate('cascade');
                $table->foreign('subscriber_subscription_id', 'fk_subscriptions')->references('id')->on('subscriber_subscription')->onDelete('cascade')->onUpdate('cascade');
                $table->unique(['campaign_id', 'subscriber_subscription_id'], 'ui_campaign_subscription');

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
        if ($this->schema->hasTable('campaign_subscriber_subscription')) {
            $this->schema->drop('campaign_subscriber_subscription');
        }
    }
}
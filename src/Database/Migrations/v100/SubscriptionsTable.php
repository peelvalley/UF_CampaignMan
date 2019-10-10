<?php

namespace UserFrosting\Sprinkle\CampaignMan\Database\Migrations\v100;

use Illuminate\Database\Schema\Blueprint;
use UserFrosting\Sprinkle\Core\Database\Migration;

class SubscriptionsTable extends Migration
{
    public static $dependencies = [
        '\UserFrosting\Sprinkle\CampaignMan\Database\Migrations\v100\SubscribersTable',
        '\UserFrosting\Sprinkle\CampaignMan\Database\Migrations\v100\MailingListsTable'
    ];

    /**
    * {@inheritdoc}
    */
    public function up()
    {
        if (!$this->schema->hasTable('subscriber_subscription')) {
            $this->schema->create('subscriber_subscription', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('mailing_list_id')->unsigned();
                $table->integer('subscriber_id')->unsigned();
                $table->string('name', 255)->default(null)->nullable();
                $table->unsignedInteger('group_id')->default(null)->nullable();
                $table->boolean('enabled')->default(TRUE);

                $table->json('data')->nullable();

                $table->timestamps();
                $table->index('enabled');
                $table->unique(['mailing_list_id', 'subscriber_id']);


                $table->foreign('mailing_list_id')->references('id')->on('mailing_lists')->onDelete('cascade')->onUpdate('cascade');
                $table->foreign('subscriber_id')->references('id')->on('subscribers')->onDelete('cascade')->onUpdate('cascade');
                $table->foreign('group_id')->references('id')->on('groups')->onDelete('cascade')->onUpdate('cascade');

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
        if ($this->schema->hasTable('subscriber_subscription')) {
            $this->schema->drop('subscriber_subscription');
        }
    }
}
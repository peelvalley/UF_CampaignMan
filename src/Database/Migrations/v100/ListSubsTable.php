<?php

namespace UserFrosting\Sprinkle\CampaignMan\Database\Migrations\v100;

use Illuminate\Database\Schema\Blueprint;
use UserFrosting\Sprinkle\Core\Database\Migration;

class ListSubsTable extends Migration
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
        if (!$this->schema->hasTable('list_subs')) {
            $this->schema->create('list_subs', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('list_id')->unsigned();
                $table->integer('subscriber_id')->unsigned();
                $table->boolean('enabled')->default(TRUE);

                $table->timestamps();
                $table->index('enabled');

                $table->foreign('list_id')->references('id')->on('mailing_lists')->onDelete('cascade')->onUpdate('cascade');
                $table->foreign('subscriber_id')->references('id')->on('subscribers')->onDelete('cascade')->onUpdate('cascade');

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
        if ($this->schema->hasTable('list_subs')) {
            $this->schema->drop('list_subs');
        }
    }
}
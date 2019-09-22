<?php

namespace UserFrosting\Sprinkle\CampaignMan\Database\Migrations\v100;

use Illuminate\Database\Schema\Blueprint;
use UserFrosting\Sprinkle\Core\Database\Migration;

class SubscriberVerificationsTable extends Migration
{

    public static $dependencies = [
        '\UserFrosting\Sprinkle\CampaignMan\Database\Migrations\v100\SubscribersTable',
    ];

    /**
     * {@inheritdoc}
     */
    public function up()
    {
        if (!$this->schema->hasTable('subscriber_verifications')) {
            $this->schema->create('subscriber_verifications', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('subscriber_id')->unsigned();
                $table->string('type', 5);
                $table->string('hash');
                $table->boolean('completed')->default(0);
                $table->timestamp('expires_at')->nullable();
                $table->timestamp('completed_at')->nullable();
                $table->timestamps();

                $table->engine = 'InnoDB';
                $table->collation = 'utf8_unicode_ci';
                $table->charset = 'utf8';
                $table->foreign('subscriber_id')->references('id')->on('subscribers');
                $table->index('subscriber_id');
                $table->index('hash');
            });
        }
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        if ($this->schema->hasTable('subscriber_verifications')) {
            $this->schema->drop('subscriber_verifications');
        }
    }
}
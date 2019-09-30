<?php

namespace UserFrosting\Sprinkle\CampaignMan\Database\Migrations\v100;

use Illuminate\Database\Schema\Blueprint;
use UserFrosting\Sprinkle\Core\Database\Migration;

class MailingQueueTable extends Migration
{

    /**
    * {@inheritdoc}
    */
    public function up()
    {
        if (!$this->schema->hasTable('mailing_queue')) {
            $this->schema->create('mailing_queue', function (Blueprint $table) {
                $table->increments('id');
                $table->json('data')->nullable();

                $table->timestamps();

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
        if ($this->schema->hasTable('mailing_queue')) {
            $this->schema->drop('mailing_queue');
        }
    }
}
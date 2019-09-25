<?php

namespace UserFrosting\Sprinkle\CampaignMan\Database\Migrations\v100;

use Illuminate\Database\Schema\Blueprint;
use UserFrosting\Sprinkle\Core\Database\Migration;

class CampaignsTable extends Migration
{
    /**
    * {@inheritdoc}
    */
    public function up()
    {
        if (!$this->schema->hasTable('campaigns')) {
            $this->schema->create('campaigns', function (Blueprint $table) {
                $table->increments('id');
                $table->string('status', 15);
                $table->json('metadata')->nullable();

                $table->softDeletes();
                $table->timestamps();

                $table->index('status');

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
        if ($this->schema->hasTable('campaigns')) {
            $this->schema->drop('campaigns');
        }
    }
}
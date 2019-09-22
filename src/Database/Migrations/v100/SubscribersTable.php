<?php

namespace UserFrosting\Sprinkle\CampaignMan\Database\Migrations\v100;

use Illuminate\Database\Schema\Blueprint;
use UserFrosting\System\Bakery\Migration;

class SubscribersTable extends Migration
{
    /**
    * {@inheritdoc}
    */
    public function up()
    {
        if (!$this->schema->hasTable('subscribers')) {
            $this->schema->create('subscribers', function (Blueprint $table) {
                $table->increments('id');
                $table->string('email', 255);
                $table->json('metadata')->nullable();
                $table->unique('email');
                $table->index('email');

                $table->softDeletes();
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
        if ($this->schema->hasTable('subscribers')) {
            $this->schema->drop('subscribers');
        }
    }
}
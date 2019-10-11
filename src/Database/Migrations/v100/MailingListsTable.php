<?php

namespace UserFrosting\Sprinkle\CampaignMan\Database\Migrations\v100;

use Illuminate\Database\Schema\Blueprint;
use UserFrosting\Sprinkle\Core\Database\Migration;

class MailingListsTable extends Migration
{
    public static $dependencies = [
        '\UserFrosting\Sprinkle\Account\Database\Migrations\v400\GroupsTable',
    ];

    /**
    * {@inheritdoc}
    */
    public function up()
    {
        if (!$this->schema->hasTable('mailing_lists')) {
            $this->schema->create('mailing_lists', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('group_id')->default(null)->nullable();
                $table->string('name', 255);
                $table->string('slug', 255);
                $table->string('description', 255);
                $table->json('metadata')->nullable();
                $table->index('slug');
                $table->index('group_id');
                $table->foreign('group_id')->references('id')->on('groups')->onDelete('cascade')->onUpdate('cascade');
                $table->softDeletes();
                $table->timestamps();

                $table->unique(['group_id', 'slug']);

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
        if ($this->schema->hasTable('mailing_lists')) {
            $this->schema->drop('mailing_lists');
        }
    }
}
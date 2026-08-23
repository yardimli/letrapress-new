<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('prowly_countries')) {
            Schema::create('prowly_countries', function (Blueprint $table) {
                $table->id(); $table->string('country')->nullable()->index(); $table->integer('record_count')->default(0);
            });
        }
        if (! Schema::hasTable('prowly_cities')) {
            Schema::create('prowly_cities', function (Blueprint $table) {
                $table->id(); $table->string('city')->nullable()->index(); $table->string('country')->nullable()->index(); $table->integer('record_count')->default(0); $table->integer('country_id')->default(0)->index();
            });
        }
        if (! Schema::hasTable('prowly_languages')) {
            Schema::create('prowly_languages', function (Blueprint $table) {
                $table->id(); $table->string('language')->nullable()->index(); $table->integer('record_count')->default(0);
            });
        }
        if (! Schema::hasTable('prowly_topics')) {
            Schema::create('prowly_topics', function (Blueprint $table) {
                $table->id(); $table->string('topic')->nullable()->index(); $table->integer('record_count')->default(0);
            });
        }
        if (! Schema::hasTable('prowly_titles')) {
            Schema::create('prowly_titles', function (Blueprint $table) {
                $table->id(); $table->string('title')->nullable()->index(); $table->integer('record_count')->default(0);
            });
        }
        if (! Schema::hasTable('prowly_outlet_types')) {
            Schema::create('prowly_outlet_types', function (Blueprint $table) {
                $table->id(); $table->string('outlet_type')->nullable()->index(); $table->integer('record_count')->default(0);
            });
        }
        if (! Schema::hasTable('prowly_journalists')) {
            Schema::create('prowly_journalists', function (Blueprint $table) {
                $table->id(); $table->boolean('waiting_for_update')->default(false); $table->integer('city_id')->default(0); $table->string('journalist_name')->nullable(); $table->string('first_name')->nullable(); $table->string('last_name')->nullable(); $table->integer('title_id')->default(0); $table->integer('influence_score')->default(0)->index(); $table->integer('media_type_id')->default(0); $table->string('email')->nullable(); $table->string('phone')->nullable(); $table->string('journalist_picture_url')->nullable(); $table->integer('country_id')->default(0); $table->string('state')->nullable(); $table->string('prowly_id')->nullable()->index(); $table->string('outlet_name')->nullable(); $table->string('outlet_id')->default('0')->index(); $table->timestamp('update_time')->nullable();
            });
        }
        if (! Schema::hasTable('prowly_outlets')) {
            Schema::create('prowly_outlets', function (Blueprint $table) {
                $table->id(); $table->boolean('waiting_for_update')->default(false); $table->integer('city_id')->default(0); $table->string('outlet_name')->nullable(); $table->integer('influence_score')->default(0); $table->integer('media_type_id')->default(0); $table->string('outlet_url')->nullable(); $table->string('outlet_picture_url')->nullable(); $table->string('email')->nullable(); $table->string('phone')->nullable(); $table->integer('country_id')->default(0); $table->string('state')->nullable(); $table->string('prowly_id')->nullable()->index(); $table->timestamp('update_time')->nullable();
            });
        }
        $this->createReferenceTable('prowly_language_list', 'journalist_id', 'language_id');
        $this->createReferenceTable('prowly_topic_list', 'journalist_id', 'topic_id');
        $this->createReferenceTable('prowly_outlet_language_list', 'outlet_id', 'language_id');
        $this->createReferenceTable('prowly_outlet_topic_list', 'outlet_id', 'topic_id');
        if (! Schema::hasTable('prowly_social_medias')) {
            Schema::create('prowly_social_medias', function (Blueprint $table) { $table->id(); $table->string('social_link')->nullable(); $table->string('social_type')->nullable(); $table->integer('journalist_id')->default(0)->index(); });
        }
        if (! Schema::hasTable('prowly_outlet_social_medias')) {
            Schema::create('prowly_outlet_social_medias', function (Blueprint $table) { $table->id(); $table->string('social_link')->nullable(); $table->string('social_type')->nullable(); $table->integer('outlet_id')->default(0)->index(); });
        }
    }

    private function createReferenceTable(string $name, string $left, string $right): void
    {
        if (! Schema::hasTable($name)) {
            Schema::create($name, function (Blueprint $table) use ($left, $right) { $table->id(); $table->integer($left)->default(0)->index(); $table->integer($right)->default(0)->index(); });
        }
    }

    public function down(): void
    {
        foreach (['prowly_outlet_social_medias', 'prowly_social_medias', 'prowly_outlet_topic_list', 'prowly_outlet_language_list', 'prowly_topic_list', 'prowly_language_list', 'prowly_outlets', 'prowly_journalists', 'prowly_outlet_types', 'prowly_titles', 'prowly_topics', 'prowly_languages', 'prowly_cities', 'prowly_countries'] as $table) {
            Schema::dropIfExists($table);
        }
    }
};

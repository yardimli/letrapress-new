<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('press_releases', function (Blueprint $table) {
            $table->string('release_type', 20)->default('release')->after('content');
            $table->string('distribution', 20)->default('newsroom')->after('release_type');
            $table->string('status', 30)->default('draft')->after('distribution');
            $table->string('template_key')->nullable()->after('status');
            $table->json('analysis')->nullable()->after('template_key');
            $table->unsignedInteger('recipient_count')->default(0)->after('analysis');
            $table->timestamp('targeted_at')->nullable()->after('recipient_count');
            $table->unsignedBigInteger('news_room_id')->nullable()->after('targeted_at');
        });

        Schema::create('press_release_recipients', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('press_release_id')->index();
            $table->string('recipient_type', 20);
            $table->unsignedBigInteger('recipient_id');
            $table->string('source', 20)->default('manual');
            $table->unsignedTinyInteger('match_score')->nullable();
            $table->string('match_reason')->nullable();
            $table->timestamps();
            $table->unique(['press_release_id', 'recipient_type', 'recipient_id'], 'press_release_recipient_unique');
            $table->index(['recipient_type', 'recipient_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('press_release_recipients');
        Schema::table('press_releases', function (Blueprint $table) {
            $table->dropColumn(['release_type', 'distribution', 'status', 'template_key', 'analysis', 'recipient_count', 'targeted_at', 'news_room_id']);
        });
    }
};

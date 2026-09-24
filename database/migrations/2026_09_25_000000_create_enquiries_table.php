<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('enquiries', function (Blueprint $table): void {
            $table->id();
            $table->string('name', 120);
            $table->string('email', 180)->index();
            $table->string('organisation', 180)->nullable();
            $table->string('phone', 80)->nullable();
            $table->string('service', 120);
            $table->string('timeframe', 80)->nullable();
            $table->string('referral', 80)->nullable();
            $table->text('message');

            $table->unsignedTinyInteger('spam_score')->default(0)->index();
            $table->unsignedTinyInteger('original_spam_score')->default(0);
            $table->string('spam_status', 30)->default('delivered')->index();
            $table->json('score_reasons')->nullable();
            $table->string('review_status', 20)->default('unreviewed')->index();

            $table->string('ip_hash', 64)->nullable()->index();
            $table->char('ip_country', 2)->nullable()->index();
            $table->char('email_country', 2)->nullable();
            $table->json('link_countries')->nullable();
            $table->string('message_fingerprint', 64)->index();
            $table->text('user_agent')->nullable();
            $table->boolean('turnstile_passed')->nullable();
            $table->unsignedInteger('completion_seconds')->nullable();

            $table->timestamp('notification_sent_at')->nullable();
            $table->timestamp('confirmation_sent_at')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('last_scored_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enquiries');
    }
};

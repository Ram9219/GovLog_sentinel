<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notification_queues', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('log_id')->constrained('server_logs');
            $table->jsonb('channels');
            $table->jsonb('recipients');
            $table->enum('status', ['pending', 'sent', 'failed', 'retrying'])
                  ->default('pending');
            $table->integer('retry_count')->default(0);
            $table->jsonb('error_log')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notification_queues');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('CREATE EXTENSION IF NOT EXISTS "uuid-ossp";');

        Schema::create('server_logs', function (Blueprint $table): void {
            $table->id();
            $table->uuid('log_entry_id')->default(DB::raw('uuid_generate_v4()'));
            $table->timestamp('timestamp')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->string('source_ip', 45);
            $table->foreignId('user_id')->nullable()->constrained();
            $table->string('action_type', 150);
            $table->enum('severity', ['debug', 'info', 'warning', 'error', 'critical', 'emergency'])
                ->default('info');
            $table->string('classification', 100);
            $table->text('message');
            $table->jsonb('context');
            $table->jsonb('metadata');
            $table->string('hash', 64);
            $table->string('previous_hash', 64)->nullable();
            $table->string('request_id', 36)->index();
            $table->boolean('is_notified')->default(false);
            $table->jsonb('notification_results')->nullable();
            $table->timestamp('retention_date')->nullable();
            $table->timestamps();

            $table->index(['severity', 'created_at']);
            $table->index(['classification', 'created_at']);
            $table->index('timestamp');
            $table->index('log_entry_id');
        });

        DB::statement('ALTER TABLE server_logs ALTER COLUMN source_ip TYPE inet USING source_ip::inet;');
        DB::statement('CREATE INDEX idx_logs_message_gin ON server_logs USING GIN(to_tsvector(\'english\', message));');
        DB::statement('CREATE INDEX idx_logs_context_gin ON server_logs USING GIN(context);');
    }

    public function down(): void
    {
        Schema::dropIfExists('server_logs');
    }
};

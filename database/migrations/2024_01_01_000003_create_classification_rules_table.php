<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('classification_rules', function (Blueprint $table): void {
            $table->id();
            $table->string('name', 100);
            $table->string('classification', 100);
            $table->enum('severity', ['info', 'warning', 'error', 'critical']);
            $table->jsonb('patterns');
            $table->jsonb('conditions')->nullable();
            $table->integer('priority')->default(0);
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->timestamps();

            $table->index(['is_active', 'priority']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('classification_rules');
    }
};

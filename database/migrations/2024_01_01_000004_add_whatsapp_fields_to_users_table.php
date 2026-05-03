<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->string('phone_number')->nullable()->unique()->after('email');
            $table->string('whatsapp_number')->nullable()->unique()->after('phone_number');
            $table->boolean('sms_notifications')->default(false)->after('whatsapp_number');
            $table->boolean('whatsapp_notifications')->default(false)->after('sms_notifications');
            $table->jsonb('notification_preferences')->nullable()->after('whatsapp_notifications');
            $table->timestamp('phone_verified_at')->nullable()->after('notification_preferences');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->dropColumn([
                'phone_number',
                'whatsapp_number',
                'sms_notifications',
                'whatsapp_notifications',
                'notification_preferences',
                'phone_verified_at',
            ]);
        });
    }
};

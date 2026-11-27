<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('phelon_staff_users')) {
            Schema::create('phelon_staff_users', function (Blueprint $table) {
                $table->id();
                $table->string('email', 254)->unique();
                $table->string('display_name', 100);
                $table->string('password_hash');
                $table->boolean('active')->default(true);
                $table->timestamp('created_at')->useCurrent();
            });
        }
        if (!Schema::hasTable('phelon_discovery_bookings')) {
            Schema::create('phelon_discovery_bookings', function (Blueprint $table) {
                $table->id();
                $table->char('reference', 24)->unique();
                $table->dateTime('starts_at_utc')->unique();
                $table->dateTime('ends_at_utc');
                $table->string('service_key', 64)->nullable();
                $table->string('subtype_key', 64)->nullable();
                $table->string('name', 100);
                $table->string('email', 254);
                $table->string('phone', 32);
                $table->char('state_code', 2);
                $table->string('visitor_timezone', 80);
                $table->string('concern', 300);
                $table->timestamp('created_at')->useCurrent();
            });
        }
        Schema::create('phelon_intakes', function (Blueprint $table) {
            $table->id();
            $table->string('appointment_ref', 128)->unique();
            $table->longText('answers');
            $table->text('files')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        throw new LogicException('This migration may adopt existing tables and stores customer data. Revert the application without dropping the tables.');
    }
};

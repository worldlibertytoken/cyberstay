<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            if (! Schema::hasColumn('customers', 'father_name')) {
                $table->string('father_name')->nullable()->after('name');
            }
        });

        Schema::table('bookings', function (Blueprint $table) {
            if (! Schema::hasColumn('bookings', 'vehicle_number')) {
                $table->string('vehicle_number')->nullable()->after('notes');
            }

            if (! Schema::hasColumn('bookings', 'males')) {
                $table->unsignedInteger('males')->default(1)->after('vehicle_number');
            }

            if (! Schema::hasColumn('bookings', 'females')) {
                $table->unsignedInteger('females')->default(0)->after('males');
            }

            if (! Schema::hasColumn('bookings', 'children')) {
                $table->unsignedInteger('children')->default(0)->after('females');
            }

            if (! Schema::hasColumn('bookings', 'id_card_front')) {
                $table->string('id_card_front')->nullable()->after('children');
            }

            if (! Schema::hasColumn('bookings', 'id_card_back')) {
                $table->string('id_card_back')->nullable()->after('id_card_front');
            }
        });

        if (! Schema::hasTable('booking_companions')) {
            Schema::create('booking_companions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
                $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
                $table->string('name');
                $table->string('phone')->nullable();
                $table->string('id_card_front')->nullable();
                $table->string('id_card_back')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_companions');

        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn([
                'vehicle_number',
                'males',
                'females',
                'children',
                'id_card_front',
                'id_card_back',
            ]);
        });

        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn('father_name');
        });
    }
};

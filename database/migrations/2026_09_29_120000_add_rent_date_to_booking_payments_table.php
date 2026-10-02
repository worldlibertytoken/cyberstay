<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('booking_payments')) {
            return;
        }

        if (! Schema::hasColumn('booking_payments', 'rent_date')) {
            Schema::table('booking_payments', function (Blueprint $table) {
                $table->date('rent_date')->nullable()->after('booking_item_id');
            });
        }

        Schema::table('booking_payments', function (Blueprint $table) {
            $indexes = Schema::getIndexes('booking_payments');
            $hasIndex = collect($indexes)->contains(
                fn (array $index): bool => ($index['name'] ?? '') === 'booking_payments_booking_id_rent_date_index'
            );

            if (! $hasIndex) {
                $table->index(['booking_id', 'rent_date']);
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('booking_payments') || ! Schema::hasColumn('booking_payments', 'rent_date')) {
            return;
        }

        Schema::table('booking_payments', function (Blueprint $table) {
            $indexes = Schema::getIndexes('booking_payments');
            $hasIndex = collect($indexes)->contains(
                fn (array $index): bool => ($index['name'] ?? '') === 'booking_payments_booking_id_rent_date_index'
            );

            if ($hasIndex) {
                $table->dropIndex(['booking_id', 'rent_date']);
            }

            $table->dropColumn('rent_date');
        });
    }
};

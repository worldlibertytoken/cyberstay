<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            if (! Schema::hasColumn('bookings', 'checkout_note')) {
                $table->text('checkout_note')->nullable()->after('notes');
            }

            if (! Schema::hasColumn('bookings', 'room_paid_amount')) {
                $table->decimal('room_paid_amount', 12, 2)->default(0)->after('checkout_note');
            }

            if (! Schema::hasColumn('bookings', 'extras_paid_amount')) {
                $table->decimal('extras_paid_amount', 12, 2)->default(0)->after('room_paid_amount');
            }

            if (! Schema::hasColumn('bookings', 'balance_due')) {
                $table->decimal('balance_due', 12, 2)->default(0)->after('extras_paid_amount');
            }

            if (! Schema::hasColumn('bookings', 'payment_status')) {
                $table->string('payment_status')->default('unpaid')->after('balance_due');
            }

            if (! Schema::hasColumn('bookings', 'checked_out_at')) {
                $table->timestamp('checked_out_at')->nullable()->after('payment_status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn([
                'checkout_note',
                'room_paid_amount',
                'extras_paid_amount',
                'balance_due',
                'payment_status',
                'checked_out_at',
            ]);
        });
    }
};

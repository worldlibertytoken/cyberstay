<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->timestamp('checked_in_at')->nullable()->after('payment_status');
        });

        DB::table('bookings')
            ->where('status', 'checked_in')
            ->whereNull('checked_in_at')
            ->update([
                'checked_in_at' => DB::raw('created_at'),
            ]);
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('checked_in_at');
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('booking_companions', function (Blueprint $table) {
            if (! Schema::hasColumn('booking_companions', 'father_name')) {
                $table->string('father_name')->nullable()->after('name');
            }
            if (! Schema::hasColumn('booking_companions', 'address')) {
                $table->text('address')->nullable()->after('cnic');
            }
        });
    }

    public function down(): void
    {
        Schema::table('booking_companions', function (Blueprint $table) {
            if (Schema::hasColumn('booking_companions', 'father_name')) {
                $table->dropColumn('father_name');
            }
            if (Schema::hasColumn('booking_companions', 'address')) {
                $table->dropColumn('address');
            }
        });
    }
};

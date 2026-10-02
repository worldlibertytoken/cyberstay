<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('booking_companions', function (Blueprint $table) {
            if (! Schema::hasColumn('booking_companions', 'cnic')) {
                $table->string('cnic')->nullable()->after('name');
            }

            if (Schema::hasColumn('booking_companions', 'phone')) {
                $table->dropColumn('phone');
            }
        });
    }

    public function down(): void
    {
        Schema::table('booking_companions', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('name');
            $table->dropColumn('cnic');
        });
    }
};

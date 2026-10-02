<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            if (! Schema::hasColumn('tenants', 'logo')) {
                $table->string('logo')->nullable()->after('address');
            }

            if (! Schema::hasColumn('tenants', 'website')) {
                $table->string('website')->nullable()->after('logo');
            }

            if (! Schema::hasColumn('tenants', 'tax_id')) {
                $table->string('tax_id')->nullable()->after('website');
            }

            if (! Schema::hasColumn('tenants', 'description')) {
                $table->text('description')->nullable()->after('tax_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->dropColumn(['logo', 'website', 'tax_id', 'description']);
        });
    }
};

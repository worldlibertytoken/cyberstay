<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('salary_payments', function (Blueprint $table) {
            if (! Schema::hasColumn('salary_payments', 'expense_id')) {
                $table->foreignId('expense_id')
                    ->nullable()
                    ->after('employee_id')
                    ->constrained('expenses')
                    ->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('salary_payments', function (Blueprint $table) {
            $table->dropConstrainedForeignId('expense_id');
        });
    }
};

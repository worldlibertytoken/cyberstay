<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('bookings')) {
            return;
        }

        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('room_id')->constrained()->restrictOnDelete();
            $table->foreignId('customer_id')->constrained()->restrictOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->date('check_in');
            $table->date('check_out');
            $table->unsignedInteger('nights');
            $table->decimal('price_per_night', 12, 2);
            $table->decimal('room_amount', 12, 2);
            $table->decimal('extras_amount', 12, 2)->default(0);
            $table->decimal('grand_total', 12, 2);
            $table->string('status')->default('reserved');
            $table->text('notes')->nullable();
            $table->text('checkout_note')->nullable();
            $table->decimal('room_paid_amount', 12, 2)->default(0);
            $table->decimal('extras_paid_amount', 12, 2)->default(0);
            $table->decimal('balance_due', 12, 2)->default(0);
            $table->string('payment_status')->default('unpaid');
            $table->timestamp('checked_out_at')->nullable();
            $table->string('vehicle_number')->nullable();
            $table->unsignedInteger('males')->default(1);
            $table->unsignedInteger('females')->default(0);
            $table->unsignedInteger('children')->default(0);
            $table->string('id_card_front')->nullable();
            $table->string('id_card_back')->nullable();
            $table->timestamps();

            $table->index(['tenant_id', 'status']);
            $table->index(['room_id', 'check_in', 'check_out']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};

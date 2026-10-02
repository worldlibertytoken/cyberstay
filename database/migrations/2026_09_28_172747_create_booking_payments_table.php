<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('booking_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
            $table->foreignId('received_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('type');
            $table->decimal('amount', 12, 2);
            $table->string('method');
            $table->string('note')->nullable();
            $table->timestamp('paid_at');
            $table->timestamps();

            $table->index(['booking_id', 'paid_at']);
            $table->index(['tenant_id', 'paid_at']);
        });

        $now = now();

        DB::table('bookings')
            ->select(['id', 'tenant_id', 'room_paid_amount', 'extras_paid_amount', 'checked_out_at', 'created_at', 'updated_at'])
            ->orderBy('id')
            ->chunkById(100, function ($bookings) use ($now): void {
                foreach ($bookings as $booking) {
                    $paidAt = $booking->checked_out_at ?: ($booking->updated_at ?: $now);

                    if ((float) $booking->room_paid_amount > 0) {
                        DB::table('booking_payments')->insert([
                            'tenant_id' => $booking->tenant_id,
                            'booking_id' => $booking->id,
                            'received_by' => null,
                            'type' => 'room',
                            'amount' => $booking->room_paid_amount,
                            'method' => 'cash',
                            'note' => 'Imported from previous paid rent',
                            'paid_at' => $paidAt,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ]);
                    }

                    if ((float) $booking->extras_paid_amount > 0) {
                        DB::table('booking_payments')->insert([
                            'tenant_id' => $booking->tenant_id,
                            'booking_id' => $booking->id,
                            'received_by' => null,
                            'type' => 'extras',
                            'amount' => $booking->extras_paid_amount,
                            'method' => 'cash',
                            'note' => 'Imported from previous paid extras',
                            'paid_at' => $paidAt,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ]);
                    }
                }
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_payments');
    }
};

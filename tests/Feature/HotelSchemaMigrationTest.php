<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class HotelSchemaMigrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_fresh_migrate_creates_all_application_tables(): void
    {
        foreach ([
            'users',
            'password_reset_tokens',
            'sessions',
            'cache',
            'cache_locks',
            'jobs',
            'job_batches',
            'failed_jobs',
            'tenants',
            'rooms',
            'customers',
            'bookings',
            'pos_items',
            'booking_items',
            'expenses',
            'employees',
            'salary_payments',
            'booking_companions',
        ] as $table) {
            $this->assertTrue(Schema::hasTable($table), "Missing table [{$table}]");
        }

        $this->assertTrue(Schema::hasColumns('tenants', ['logo', 'website', 'tax_id', 'description']));
        $this->assertTrue(Schema::hasColumns('users', ['tenant_id', 'role', 'phone', 'is_active']));
        $this->assertTrue(Schema::hasColumns('customers', ['father_name', 'cnic']));
        $this->assertTrue(Schema::hasColumns('rooms', ['bed_type', 'max_capacity']));
        $this->assertTrue(Schema::hasColumns('bookings', [
            'vehicle_number',
            'males',
            'checkout_note',
            'checked_in_at',
            'room_paid_amount',
            'extras_paid_amount',
            'balance_due',
            'payment_status',
            'checked_out_at',
        ]));
        $this->assertTrue(Schema::hasColumns('salary_payments', ['expense_id']));
        $this->assertTrue(Schema::hasColumns('booking_companions', ['cnic']));
        $this->assertFalse(Schema::hasColumn('booking_companions', 'phone'));
        $this->assertTrue(Schema::hasTable('booking_payments'));
        $this->assertTrue(Schema::hasColumns('booking_payments', ['booking_item_id', 'rent_date', 'type', 'method', 'amount']));
    }
}

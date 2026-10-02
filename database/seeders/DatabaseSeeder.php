<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Expense;
use App\Models\PosItem;
use App\Models\Room;
use App\Models\Tenant;
use App\Models\User;
use App\Support\TenantContext;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@hoteldesk.test',
            'password' => 'password',
            'role' => 'super_admin',
            'is_active' => true,
        ]);

        $palace = Tenant::create([
            'name' => 'Grand Palace Hotel',
            'slug' => 'grand-palace',
            'phone' => '0300-1111111',
            'email' => 'front@grandpalace.test',
            'city' => 'Lahore',
            'address' => 'Mall Road, Lahore',
            'is_active' => true,
        ]);

        $inn = Tenant::create([
            'name' => 'City Inn',
            'slug' => 'city-inn',
            'phone' => '0300-2222222',
            'email' => 'hello@cityinn.test',
            'city' => 'Karachi',
            'address' => 'Saddar, Karachi',
            'is_active' => true,
        ]);

        $this->seedHotel($palace, [
            'owner' => ['name' => 'Palace Owner', 'email' => 'owner@grandpalace.test'],
            'receptionist' => ['name' => 'Ayesha Reception', 'email' => 'reception@grandpalace.test'],
        ], true);

        $this->seedHotel($inn, [
            'owner' => ['name' => 'Inn Owner', 'email' => 'owner@cityinn.test'],
        ], false);
    }

    private function seedHotel(Tenant $hotel, array $users, bool $withSampleStay): void
    {
        TenantContext::set($hotel->id);

        User::create([
            'tenant_id' => $hotel->id,
            'name' => $users['owner']['name'],
            'email' => $users['owner']['email'],
            'password' => 'password',
            'role' => 'owner',
            'is_active' => true,
        ]);

        if (isset($users['receptionist'])) {
            User::create([
                'tenant_id' => $hotel->id,
                'name' => $users['receptionist']['name'],
                'email' => $users['receptionist']['email'],
                'password' => 'password',
                'role' => 'receptionist',
                'phone' => '0301-5551234',
                'is_active' => true,
            ]);
        }

        foreach ([
            ['101', 'single', '1'],
            ['102', 'double', '1'],
            ['103', 'twin', '1'],
            ['201', 'deluxe', '2'],
            ['202', 'suite', '2'],
            ['203', 'family', '2'],
        ] as [$number, $type, $floor]) {
            Room::create([
                'tenant_id' => $hotel->id,
                'number' => $number,
                'type' => $type,
                'floor' => $floor,
                'status' => 'available',
            ]);
        }

        foreach ([
            ['Tea', 80],
            ['Water', 50],
            ['Breakfast', 450],
            ['Lunch', 700],
            ['Dinner', 850],
        ] as [$name, $price]) {
            PosItem::create([
                'tenant_id' => $hotel->id,
                'name' => $name,
                'price' => $price,
                'is_active' => true,
            ]);
        }

        $ali = Customer::create([
            'tenant_id' => $hotel->id,
            'name' => 'Ali Raza',
            'phone' => '0300-1234567',
            'cnic' => '35202-1234567-1',
            'address' => 'Johar Town',
        ]);

        Customer::create([
            'tenant_id' => $hotel->id,
            'name' => 'Sara Khan',
            'phone' => '0321-7654321',
            'cnic' => '35202-7654321-2',
            'address' => 'DHA',
        ]);

        Employee::create([
            'tenant_id' => $hotel->id,
            'name' => 'Imran Housekeeping',
            'phone' => '0300-9998877',
            'position' => 'Housekeeping',
            'salary' => 35000,
            'hire_date' => now()->subMonths(8),
            'status' => 'active',
        ]);

        Expense::create([
            'tenant_id' => $hotel->id,
            'title' => 'Grocery purchase',
            'category' => 'purchase',
            'amount' => 4200,
            'expense_date' => now()->toDateString(),
        ]);

        if ($withSampleStay) {
            $room = Room::where('number', '101')->first();
            $nights = 2;
            $rate = 6500;

            Booking::create([
                'tenant_id' => $hotel->id,
                'room_id' => $room->id,
                'customer_id' => $ali->id,
                'check_in' => now()->toDateString(),
                'check_out' => now()->addDays($nights)->toDateString(),
                'nights' => $nights,
                'price_per_night' => $rate,
                'room_amount' => $nights * $rate,
                'extras_amount' => 0,
                'grand_total' => $nights * $rate,
                'status' => 'checked_in',
            ]);
        }

        TenantContext::set(null);
    }
}

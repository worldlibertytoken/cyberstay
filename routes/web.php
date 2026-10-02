<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CNICOCRController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DashboardEntryController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\GuestBookingExportController;
use App\Http\Controllers\HotelController;
use App\Http\Controllers\HotelSettingsController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\StaffController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->get('/bookings/{booking}/guest-copy', GuestBookingExportController::class)->name('bookings.guest-copy');

Route::get('/', function () {
    return Auth::check()
    ? redirect()->route(Auth::user()?->role === 'super_admin' && ! session('current_tenant_id') ? 'hotels.index' : 'dashboard')
        : redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/login/quick', [AuthController::class, 'quickLogin'])->name('login.quick');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware('auth')->group(function () {
    Route::middleware('super')->group(function () {
        Route::get('/hotels', [HotelController::class, 'index'])->name('hotels.index');
        Route::get('/hotels/create', [HotelController::class, 'create'])->name('hotels.create');
        Route::post('/hotels', [HotelController::class, 'store'])->name('hotels.store');
        Route::get('/hotels/{hotel}/edit', [HotelController::class, 'edit'])->name('hotels.edit');
        Route::put('/hotels/{hotel}', [HotelController::class, 'update'])->name('hotels.update');
        Route::post('/hotels/{hotel}/enter', [HotelController::class, 'enter'])->name('hotels.enter');
        Route::post('/hotels/leave', [HotelController::class, 'leave'])->name('hotels.leave');
    });

    Route::middleware('tenant')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard/entries/{scope}', [DashboardEntryController::class, 'index'])
            ->whereIn('scope', ['arrivals', 'in-house', 'departures'])
            ->name('dashboard.entries.index');
        Route::get('/dashboard/entries/{scope}/download', [DashboardEntryController::class, 'download'])
            ->whereIn('scope', ['arrivals', 'in-house', 'departures'])
            ->name('dashboard.entries.download');
        Route::get('/dashboard/entries/{scope}/{booking}', [DashboardEntryController::class, 'show'])
            ->whereIn('scope', ['arrivals', 'in-house', 'departures'])
            ->name('dashboard.entries.show');
        Route::get('/dashboard/entries/{scope}/{booking}/download', [DashboardEntryController::class, 'downloadSingle'])
            ->whereIn('scope', ['arrivals', 'in-house', 'departures'])
            ->name('dashboard.entries.single.download');

        Route::resource('rooms', RoomController::class)->except(['show']);

        Route::get('/customers/search', [CustomerController::class, 'search'])->name('customers.search');
        Route::post('/customers/quick', [CustomerController::class, 'quickStore'])->name('customers.quick');
        Route::resource('customers', CustomerController::class)->except(['show']);

        Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
        Route::get('/bookings/create', [BookingController::class, 'create'])->name('bookings.create');
        Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
        Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
        Route::get('/bookings/{booking}/edit', [BookingController::class, 'edit'])->name('bookings.edit');
        Route::put('/bookings/{booking}', [BookingController::class, 'update'])->name('bookings.update');
        Route::get('/bookings/{booking}/bill', [BookingController::class, 'bill'])->name('bookings.bill');
        Route::get('/bookings/{booking}/services/download', [BookingController::class, 'downloadServices'])
            ->name('bookings.services.download');
        Route::post('/bookings/{booking}/check-in', [BookingController::class, 'checkIn'])->name('bookings.check-in');
        Route::post('/bookings/{booking}/check-out', [BookingController::class, 'checkOut'])->name('bookings.check-out');
        Route::post('/bookings/{booking}/payments', [BookingController::class, 'recordPayment'])->name('bookings.payments.store');
        Route::post('/bookings/{booking}/switch-room', [BookingController::class, 'switchRoom'])->name('bookings.switch-room');
        Route::post('/bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');

        Route::post('/api/ocr/parse-text', [CNICOCRController::class, 'parseText'])->name('ocr.parse-text');

        Route::get('/pos', [PosController::class, 'index'])->name('pos.index');
        Route::post('/pos/{booking}/items', [PosController::class, 'addItem'])->name('pos.items.store');
        Route::delete('/pos/{booking}/items/{item}', [PosController::class, 'removeItem'])->name('pos.items.destroy');
        Route::get('/pos/catalog', [PosController::class, 'catalog'])->name('pos.catalog');
        Route::post('/pos/catalog', [PosController::class, 'storeCatalogItem'])->name('pos.catalog.store');
        Route::delete('/pos/catalog/{item}', [PosController::class, 'destroyCatalogItem'])->name('pos.catalog.destroy');

        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/export/{report}', [ReportController::class, 'export'])->name('reports.export');

        Route::middleware('manager')->group(function () {
            Route::get('/expenses', [ExpenseController::class, 'index'])->name('expenses.index');
            Route::post('/expenses', [ExpenseController::class, 'store'])->name('expenses.store');
            Route::delete('/expenses/{expense}', [ExpenseController::class, 'destroy'])->name('expenses.destroy');

            Route::get('/employees', [EmployeeController::class, 'index'])->name('employees.index');
            Route::post('/employees', [EmployeeController::class, 'store'])->name('employees.store');
            Route::put('/employees/{employee}', [EmployeeController::class, 'update'])->name('employees.update');
            Route::delete('/employees/{employee}', [EmployeeController::class, 'destroy'])->name('employees.destroy');
            Route::post('/employees/{employee}/pay', [EmployeeController::class, 'pay'])->name('employees.pay');
            Route::delete('/employees/{employee}/payments/{payment}', [EmployeeController::class, 'voidPayment'])->name('employees.payments.destroy');

            Route::get('/staff', [StaffController::class, 'index'])->name('staff.index');
            Route::post('/staff', [StaffController::class, 'store'])->name('staff.store');
            Route::put('/staff/{staff}', [StaffController::class, 'update'])->name('staff.update');
            Route::delete('/staff/{staff}', [StaffController::class, 'destroy'])->name('staff.destroy');

            Route::get('/bookings-report', [ReportController::class, 'bookingsReport'])->name('bookings-report');
            Route::get('/hotel-settings', [HotelSettingsController::class, 'show'])->name('hotel-settings.show');
            Route::put('/hotel-settings', [HotelSettingsController::class, 'update'])->name('hotel-settings.update');
        });
    });
});

# CyberStay

A fast, practical, multi-tenant hotel booking system for daily front-desk work. Built with Laravel, Blade, and Tailwind.

Rooms are physical rooms only. Pricing is entered at booking time, nights and totals are calculated automatically, and overlapping bookings for the same room are blocked.

## Features

- Multi-tenant hotels (one app, many hotels)
- Dark, mobile-friendly dashboard
- Flexible nightly rate at booking
- Customer search / quick add
- POS extras attached to a stay
- Expenses, employees, and salary records
- Occupancy board and daily totals

## Run locally

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
```

SQLite is already configured. Then:

```bash
php artisan migrate --seed
npm run build
php artisan serve
```

Open http://localhost:8000

## Demo logins

Password for all accounts: `password`

| Role | Email |
| --- | --- |
| Super admin | admin@hoteldesk.test |
| Grand Palace owner | owner@grandpalace.test |
| Grand Palace receptionist | reception@grandpalace.test |
| City Inn owner | owner@cityinn.test |

Super admin can create hotels and enter any hotel. Hotel staff only see their own hotel’s data.

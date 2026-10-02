@php
    $customer = $booking?->customer;
    if (! $customer && old('customer_id')) {
        $customer = \App\Models\Customer::find(old('customer_id'));
    }

    $companionRows = old('companions');
    if ($companionRows === null) {
        $companionRows = $booking?->companions?->map(fn ($row) => [
            'id' => $row->id,
            'name' => $row->name,
            'father_name' => $row->father_name,
            'cnic' => $row->cnic,
            'address' => $row->address,
            'front' => $row->frontUrl(),
            'back' => $row->backUrl(),
        ])->values()->all() ?? [];
    }

    $roomsByFloor = $rooms->groupBy(fn ($room) => $room->floor ?: 'No floor');
@endphp
<form method="POST" action="{{ $action }}" enctype="multipart/form-data" class="grid items-start gap-6 xl:grid-cols-[minmax(0,1fr)_22rem]" id="booking-form">
    @csrf
    @if($method !== 'POST')
        @method($method)
    @endif

    <div class="space-y-5">
        <section class="card space-y-5 p-5 sm:p-6">
            <x-section-title icon="bed" hint="Today’s colour is a hint. Availability is still checked for the dates you pick.">1. Room & stay</x-section-title>

            <div>
                <div class="mb-3 flex flex-wrap items-center justify-between gap-2">
                    <label class="field-label mb-0"><x-icon name="door" size="sm" /> Choose room</label>
                    <div class="flex flex-wrap gap-2 text-[11px] text-muted">
                        <span class="inline-flex items-center gap-1"><span class="legend-dot bg-emerald-600"></span> Free</span>
                        <span class="inline-flex items-center gap-1"><span class="legend-dot bg-amber-500"></span> Hold</span>
                        <span class="inline-flex items-center gap-1"><span class="legend-dot bg-crimson"></span> Stay</span>
                    </div>
                </div>
                <div class="space-y-4">
                    @foreach($roomsByFloor as $floorLabel => $floorRooms)
                        <div>
                            <div class="mb-2 text-xs font-semibold uppercase tracking-wide text-muted">Floor: {{ $floorLabel }}</div>
                            <div class="grid grid-cols-2 gap-2 sm:grid-cols-3 lg:grid-cols-4">
                                @foreach($floorRooms as $room)
                                    @php $status = $room->occupancyStatus(); @endphp
                                    <button
                                        type="button"
                                        class="room-pick {{ $status }} {{ (string) $selectedRoom === (string) $room->id ? 'is-selected' : '' }}"
                                        data-room-id="{{ $room->id }}"
                                        data-room-capacity="{{ $room->max_capacity ?? 0 }}"
                                        @disabled($room->status === 'maintenance')
                                    >
                                        <div class="flex items-start justify-between gap-2">
                                            <div class="stat-num text-lg font-semibold">{{ $room->number }}</div>
                                            <x-status-badge :status="$status">{{ ucfirst($status) }}</x-status-badge>
                                        </div>
                                        <div class="mt-2 text-xs capitalize text-muted">{{ $room->type }} · {{ $room->bed_type }} bed</div>
                                        <div class="mt-1 text-xs text-muted">Capacity {{ $room->max_capacity }} guest{{ (int) $room->max_capacity === 1 ? '' : 's' }}</div>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
                <select class="input mt-3" name="room_id" id="room_id" required>
                    <option value="">Select room</option>
                    @foreach($roomsByFloor as $floorLabel => $floorRooms)
                        <optgroup label="Floor: {{ $floorLabel }}">
                            @foreach($floorRooms as $room)
                                <option
                                    value="{{ $room->id }}"
                                    data-room-capacity="{{ $room->max_capacity ?? 0 }}"
                                    @selected((string) $selectedRoom === (string) $room->id)
                                    @disabled($room->status === 'maintenance')
                                >
                                    {{ $room->number }} · {{ ucfirst($room->type) }} · {{ ucfirst($room->bed_type) }} bed · Cap {{ $room->max_capacity }}{{ $room->status === 'maintenance' ? ' (maintenance)' : '' }}
                                </option>
                            @endforeach
                        </optgroup>
                    @endforeach
                </select>
                <p id="room-capacity-warning" class="mt-2 hidden text-xs font-medium text-crimson"></p>
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="field-label"><x-icon name="calendar" size="sm" /> Check-in</label>
                    <input class="input" type="date" name="check_in" id="check_in" value="{{ old('check_in', optional($booking?->check_in)->format('Y-m-d') ?? now()->toDateString()) }}" required>
                </div>
                <div>
                    <label class="field-label"><x-icon name="calendar" size="sm" /> Check-out</label>
                    <input class="input" type="date" name="check_out" id="check_out" value="{{ old('check_out', optional($booking?->check_out)->format('Y-m-d') ?? now()->addDay()->toDateString()) }}" required>
                </div>
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="field-label"><x-icon name="money" size="sm" /> Price per night</label>
                    <input class="input text-xl font-semibold" type="number" min="0" step="0.01" name="price_per_night" id="price_per_night" value="{{ old('price_per_night', $booking->price_per_night ?? '') }}" required placeholder="0">
                    <p class="mt-1 text-xs text-muted">No room rate is stored. Type what this guest will pay.</p>
                </div>
                <div>
                    <label class="field-label"><x-icon name="edit" size="sm" /> Notes</label>
                    <textarea class="input min-h-[4.6rem]" name="notes" rows="2" placeholder="Late arrival, extra bed, etc.">{{ old('notes', $booking->notes ?? '') }}</textarea>
                </div>
            </div>
        </section>

        <section class="card space-y-5 p-5 sm:p-6">
            <x-section-title icon="user" hint="Search a returning guest, or fill a new one. Name and phone are required.">2. Main guest</x-section-title>
            <input type="hidden" name="customer_id" id="customer_id" value="{{ old('customer_id', $booking->customer_id ?? '') }}">

            <div class="search-box">
                <label class="field-label"><x-icon name="search" size="sm" /> Find existing customer</label>
                <input class="input" id="customer-search" placeholder="Name, phone, or ID card number" autocomplete="off">
                <div id="customer-results" class="search-results hidden"></div>
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="field-label"><x-icon name="user" size="sm" /> Name</label>
                    <input class="input" name="guest_name" id="guest_name" value="{{ old('guest_name', $customer->name ?? '') }}" required>
                </div>
                <div>
                    <label class="field-label"><x-icon name="users" size="sm" /> Father name</label>
                    <input class="input" name="father_name" id="father_name" value="{{ old('father_name', $customer->father_name ?? '') }}">
                </div>
                <div>
                    <label class="field-label"><x-icon name="phone" size="sm" /> Phone</label>
                    <input class="input" name="phone" id="guest_phone" value="{{ old('phone', $customer->phone ?? '') }}" required>
                </div>
                <div>
                    <label class="field-label"><x-icon name="id" size="sm" /> ID card number</label>
                    <input class="input" name="cnic" id="guest_cnic" value="{{ old('cnic', $customer->cnic ?? '') }}">
                </div>
                <div class="sm:col-span-2">
                    <label class="field-label"><x-icon name="map" size="sm" /> Address</label>
                    <textarea class="input" name="address" id="guest_address" rows="2">{{ old('address', $customer->address ?? '') }}</textarea>
                </div>
                <div class="sm:col-span-2">
                    <label class="field-label"><x-icon name="car" size="sm" /> Vehicle number</label>
                    <input class="input" name="vehicle_number" value="{{ old('vehicle_number', $booking->vehicle_number ?? '') }}" placeholder="Optional">
                </div>
                <div>
                    <label class="field-label"><x-icon name="image" size="sm" /> ID card front</label>
                    <label class="file-drop">
                        <x-icon name="camera" />
                        <span class="text-sm font-medium text-ink">Front photo</span>
                        <span class="text-xs">Optional · tap to upload</span>
                        <input type="file" name="id_card_front" accept="image/*">
                    </label>
                    @if($booking?->frontUrl())
                        <a class="mt-1 inline-block text-xs font-medium text-crimson" href="{{ $booking->frontUrl() }}" target="_blank">View current</a>
                    @endif
                </div>
                <div>
                    <label class="field-label"><x-icon name="image" size="sm" /> ID card back</label>
                    <label class="file-drop">
                        <x-icon name="camera" />
                        <span class="text-sm font-medium text-ink">Back photo</span>
                        <span class="text-xs">Optional · tap to upload</span>
                        <input type="file" name="id_card_back" accept="image/*">
                    </label>
                    @if($booking?->backUrl())
                        <a class="mt-1 inline-block text-xs font-medium text-crimson" href="{{ $booking->backUrl() }}" target="_blank">View current</a>
                    @endif
                </div>
            </div>
        </section>

        <section class="card space-y-5 p-5 sm:p-6">
            <x-section-title icon="users" tone="amber" hint="At least one person must be staying.">3. Persons staying</x-section-title>
            <div class="grid grid-cols-3 gap-3">
                <div class="counter-tile bg-rose-50">
                    <label class="field-label"><x-icon name="male" size="sm" /> Male</label>
                    <input class="input" type="number" min="0" name="males" id="males" value="{{ old('males', $booking->males ?? 1) }}" required>
                </div>
                <div class="counter-tile bg-sky-50">
                    <label class="field-label"><x-icon name="female" size="sm" /> Female</label>
                    <input class="input" type="number" min="0" name="females" id="females" value="{{ old('females', $booking->females ?? 0) }}" required>
                </div>
                <div class="counter-tile bg-amber-50">
                    <label class="field-label"><x-icon name="child" size="sm" /> Children</label>
                    <input class="input" type="number" min="0" name="children" id="children" value="{{ old('children', $booking->children ?? 0) }}" required>
                </div>
            </div>
            <div class="flex items-center justify-between rounded-2xl bg-panel-2 px-4 py-3 text-sm">
                <span class="text-muted">Total persons</span>
                <span id="persons-total" class="stat-num text-lg font-semibold">1</span>
            </div>
        </section>

        <section class="card space-y-4 p-5 sm:p-6">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <x-section-title icon="users" tone="blue" hint="Optional. Name, father/husband name, CNIC, and address — ID photos if you have them. Upload an ID card photo to auto-extract info.">4. Other guests</x-section-title>
                <button type="button" class="btn btn-ghost text-sm" id="add-companion">
                    <x-icon name="plus" size="sm" /> Add guest
                </button>
            </div>
            <div id="cnic-ocr-container"></div>
            <div id="companions" class="space-y-3">
                @forelse($companionRows as $index => $row)
                    @include('bookings._companion', ['index' => $index, 'row' => $row])
                @empty
                @endforelse
            </div>
            <p id="companions-empty" class="text-sm text-muted {{ count($companionRows) ? 'hidden' : '' }}">No extra guests yet.</p>
        </section>
    </div>

    <aside class="card bill-sticky p-5 sm:p-6">
        <x-section-title icon="receipt" hint="Updates as you type">Bill</x-section-title>
        <div class="mt-5 rounded-2xl bg-panel-2 p-4">
            <div class="text-xs font-semibold uppercase tracking-wider text-muted">Stay</div>
            <div id="bill-room" class="mt-1 font-semibold">No room selected</div>
            <div id="bill-dates" class="mt-1 text-sm text-muted">Pick dates</div>
        </div>
        <div class="mt-4 space-y-3 text-sm">
            <div class="flex justify-between"><span class="text-muted">Nights</span><span id="nights-label" class="stat-num font-semibold">0</span></div>
            <div class="flex justify-between"><span class="text-muted">Rate</span><span id="rate-label" class="stat-num font-medium">Rs 0</span></div>
            <div class="flex justify-between"><span class="text-muted">Room</span><span id="room-label" class="stat-num font-medium">Rs 0</span></div>
            @if($booking)
                <div class="flex justify-between"><span class="text-muted">Extras</span><span class="stat-num">@money($booking->extras_amount)</span></div>
            @endif
            <div class="flex justify-between"><span class="text-muted">People</span><span id="bill-people" class="font-medium">1</span></div>
        </div>
        <div class="mt-4 flex items-end justify-between border-t border-line pt-4">
            <span class="text-sm text-muted">Total</span>
            <span id="total-label" class="stat-num text-3xl font-semibold">Rs 0</span>
        </div>
        @unless($booking)
            <div class="mt-5 space-y-3 border-t border-line pt-4">
                <x-section-title icon="money" hint="Optional. Leave blank if they will pay later.">Pay now</x-section-title>
                <div>
                    <label class="field-label"><x-icon name="money" size="sm" /> Rent paid now</label>
                    <input class="input" type="number" min="0" step="0.01" name="payment_now_amount" value="{{ old('payment_now_amount') }}" placeholder="0">
                </div>
                <div>
                    <label class="field-label"><x-icon name="wallet" size="sm" /> Payment method</label>
                    <select class="input" name="payment_now_method">
                        <option value="">Select method</option>
                        <option value="cash" @selected(old('payment_now_method') === 'cash')>Cash</option>
                        <option value="online" @selected(old('payment_now_method') === 'online')>Online</option>
                    </select>
                </div>
            </div>
        @endunless
        <button class="btn btn-primary mt-6 w-full">
            <x-icon name="check" size="sm" /> {{ $booking ? 'Update booking' : 'Create booking' }}
        </button>
        <p class="mt-3 text-xs leading-relaxed text-muted">The same room cannot be booked twice on overlapping dates.</p>
    </aside>
</form>

<template id="companion-template">
    @include('bookings._companion', ['index' => '__INDEX__', 'row' => []])
</template>

<script>
const money = (n) => 'Rs ' + Math.round(n).toLocaleString();
const checkIn = document.getElementById('check_in');
const checkOut = document.getElementById('check_out');
const price = document.getElementById('price_per_night');
const roomSelect = document.getElementById('room_id');
const extras = {{ (float) ($booking->extras_amount ?? 0) }};

function nightsCount() {
    if (!checkIn.value || !checkOut.value) return 0;
    const a = new Date(checkIn.value);
    const b = new Date(checkOut.value);
    const days = Math.round((b - a) / 86400000);
    return days > 0 ? days : 0;
}

function formatDate(value) {
    if (!value) return '—';
    const d = new Date(value + 'T00:00:00');
    return d.toLocaleDateString(undefined, { day: '2-digit', month: 'short' });
}

function selectedRoomLabel() {
    const option = roomSelect.options[roomSelect.selectedIndex];
    if (!option || !option.value) return 'No room selected';
    return option.text.replace(' (maintenance)', '');
}

function refreshTotals() {
    const nights = nightsCount();
    const rate = parseFloat(price.value || '0');
    const room = nights * rate;
    document.getElementById('nights-label').textContent = nights;
    document.getElementById('rate-label').textContent = money(rate);
    document.getElementById('room-label').textContent = money(room);
    document.getElementById('total-label').textContent = money(room + extras);
    document.getElementById('bill-room').textContent = selectedRoomLabel();
    document.getElementById('bill-dates').textContent = nights
        ? formatDate(checkIn.value) + ' → ' + formatDate(checkOut.value) + ' · ' + nights + ' night' + (nights === 1 ? '' : 's')
        : 'Check-out must be after check-in';
}

function refreshPersons() {
    const males = Number(document.getElementById('males').value || 0);
    const females = Number(document.getElementById('females').value || 0);
    const children = Number(document.getElementById('children').value || 0);
    const total = males + females + children;
    document.getElementById('persons-total').textContent = total;
    document.getElementById('bill-people').textContent = total + ' · ' + males + 'M / ' + females + 'F / ' + children + 'C';
    refreshRoomCapacityWarning(total);
}

function selectedRoomCapacity() {
    const option = roomSelect.options[roomSelect.selectedIndex];
    if (!option || !option.value) {
        return null;
    }

    const capacity = Number(option.dataset.roomCapacity || 0);
    return Number.isFinite(capacity) && capacity > 0 ? capacity : null;
}

function refreshRoomCapacityWarning(totalPersons) {
    const warning = document.getElementById('room-capacity-warning');
    const capacity = selectedRoomCapacity();

    if (!warning || !capacity) {
        if (warning) {
            warning.classList.add('hidden');
            warning.textContent = '';
        }

        return;
    }

    if (totalPersons > capacity) {
        warning.textContent = 'Selected room capacity is ' + capacity + ' but persons are ' + totalPersons + '. Please choose a bigger room.';
        warning.classList.remove('hidden');
        return;
    }

    warning.classList.add('hidden');
    warning.textContent = '';
}

function markRoom(id) {
    document.querySelectorAll('.room-pick').forEach((btn) => {
        btn.classList.toggle('is-selected', String(btn.dataset.roomId) === String(id));
    });
}

[checkIn, checkOut, price].forEach((el) => el.addEventListener('input', refreshTotals));
['males', 'females', 'children'].forEach((id) => document.getElementById(id).addEventListener('input', refreshPersons));
roomSelect.addEventListener('change', () => {
    markRoom(roomSelect.value);
    refreshTotals();
});
document.querySelectorAll('.room-pick').forEach((btn) => {
    btn.addEventListener('click', () => {
        roomSelect.value = btn.dataset.roomId;
        markRoom(btn.dataset.roomId);
        refreshTotals();
    });
});
refreshTotals();
refreshPersons();
markRoom(roomSelect.value);

const searchInput = document.getElementById('customer-search');
const results = document.getElementById('customer-results');
const customerId = document.getElementById('customer_id');

function fillGuest(customer) {
    customerId.value = customer.id || '';
    document.getElementById('guest_name').value = customer.name || '';
    document.getElementById('father_name').value = customer.father_name || '';
    document.getElementById('guest_phone').value = customer.phone || '';
    document.getElementById('guest_cnic').value = customer.cnic || '';
    document.getElementById('guest_address').value = customer.address || '';
}

let timer;
searchInput.addEventListener('input', () => {
    clearTimeout(timer);
    const q = searchInput.value.trim();
    if (!q) { results.classList.add('hidden'); return; }
    timer = setTimeout(async () => {
        const res = await fetch(@json(route('customers.search')) + '?q=' + encodeURIComponent(q), {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        });
        const data = await res.json();
        if (!data.length) {
            results.innerHTML = '<div class="px-4 py-3 text-sm text-muted">No guest found. Fill details below.</div>';
            results.classList.remove('hidden');
            return;
        }
        results.innerHTML = data.map((c) => `<button type="button" class="block w-full px-4 py-3 text-left hover:bg-panel-2" data-id="${c.id}" data-name="${c.name || ''}" data-father-name="${c.father_name || ''}" data-phone="${c.phone || ''}" data-cnic="${c.cnic || ''}" data-address="${c.address || ''}"><div class="font-medium">${c.name}</div><div class="text-xs text-muted">${c.phone}${c.cnic ? ' · ' + c.cnic : ''}</div></button>`).join('');
        results.classList.remove('hidden');
        results.querySelectorAll('button').forEach((btn) => btn.addEventListener('click', () => {
            fillGuest({
                id: btn.dataset.id,
                name: btn.dataset.name,
                father_name: btn.dataset.fatherName,
                phone: btn.dataset.phone,
                cnic: btn.dataset.cnic,
                address: btn.dataset.address,
            });
            results.classList.add('hidden');
            searchInput.value = btn.dataset.name;
        }));
    }, 200);
});

document.addEventListener('click', (event) => {
    if (!event.target.closest('.search-box')) {
        results.classList.add('hidden');
    }
});

const companions = document.getElementById('companions');
const companionsEmpty = document.getElementById('companions-empty');
const template = document.getElementById('companion-template').innerHTML;
let companionIndex = {{ count($companionRows) }};

function refreshCompanionEmpty() {
    const hasRows = companions.querySelectorAll('[data-companion-row]').length > 0;
    companionsEmpty.classList.toggle('hidden', hasRows);
}

document.getElementById('add-companion').addEventListener('click', () => {
    companions.insertAdjacentHTML('beforeend', template.replaceAll('__INDEX__', String(companionIndex)));
    companionIndex += 1;
    refreshCompanionEmpty();
});

document.addEventListener('click', (event) => {
    if (event.target.closest('[data-remove-companion]')) {
        setTimeout(refreshCompanionEmpty, 0);
    }
});

function updateIdCardUploadState(input) {
    const isIdCardInput = input.name?.includes('id_card_front') || input.name?.includes('id_card_back');
    if (!isIdCardInput) {
        return;
    }

    const wrapper = input.closest('div');
    if (!wrapper) {
        return;
    }

    let status = wrapper.querySelector('[data-upload-status]');
    if (!status) {
        status = document.createElement('p');
        status.className = 'mt-1 text-xs font-medium';
        status.setAttribute('data-upload-status', '');
        wrapper.appendChild(status);
    }

    let preview = wrapper.querySelector('[data-upload-preview]');
    if (!preview) {
        preview = document.createElement('img');
        preview.setAttribute('data-upload-preview', '');
        preview.className = 'mt-2 hidden h-24 w-full rounded-xl border border-line object-cover';
        wrapper.appendChild(preview);
    }

    if (preview.dataset.objectUrl) {
        URL.revokeObjectURL(preview.dataset.objectUrl);
        delete preview.dataset.objectUrl;
    }

    const file = input.files?.[0];
    if (!file) {
        const hasCurrent = !!wrapper.querySelector('a[href]');
        status.className = 'mt-1 text-xs font-medium text-muted';
        status.textContent = hasCurrent ? 'Current image available' : 'No image selected';
        preview.classList.add('hidden');
        preview.removeAttribute('src');

        return;
    }

    status.className = 'mt-1 text-xs font-medium text-emerald-700';
    status.textContent = 'Selected: ' + file.name + ' (ready to save)';

    const objectUrl = URL.createObjectURL(file);
    preview.src = objectUrl;
    preview.dataset.objectUrl = objectUrl;
    preview.classList.remove('hidden');
}

document.addEventListener('change', (event) => {
    const input = event.target;
    if (!(input instanceof HTMLInputElement) || input.type !== 'file') {
        return;
    }

    updateIdCardUploadState(input);
});

document.querySelectorAll('input[type="file"]').forEach((input) => {
    updateIdCardUploadState(input);
});
</script>

@include('components.cnic-ocr-script')

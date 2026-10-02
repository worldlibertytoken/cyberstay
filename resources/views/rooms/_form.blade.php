<form method="POST" action="{{ $action }}" class="card mx-auto max-w-xl space-y-4 p-6">
    @csrf
    @if($method !== 'POST')
        @method($method)
    @endif
    <x-section-title icon="bed" hint="Rooms have no rates. Price is entered at booking.">Room</x-section-title>
    <div>
        <label class="field-label"><x-icon name="door" size="sm" /> Room number</label>
        <input class="input" name="number" value="{{ old('number', $room->number ?? '') }}" required>
    </div>
    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label class="field-label"><x-icon name="bed" size="sm" /> Type</label>
            <select class="input" name="type">
                @foreach(\App\Models\Room::TYPES as $type)
                    <option value="{{ $type }}" @selected(old('type', $room->type ?? 'standard') === $type)>{{ ucfirst($type) }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="field-label"><x-icon name="building" size="sm" /> Floor</label>
            <input class="input" name="floor" value="{{ old('floor', $room->floor ?? '') }}">
        </div>
    </div>
    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label class="field-label"><x-icon name="bed" size="sm" /> Bed type</label>
            <select class="input" name="bed_type">
                @foreach(\App\Models\Room::BED_TYPES as $bedType)
                    <option value="{{ $bedType }}" @selected(old('bed_type', $room->bed_type ?? 'single') === $bedType)>{{ ucfirst($bedType) }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="field-label"><x-icon name="users" size="sm" /> Max capacity</label>
            <input class="input" type="number" min="1" max="20" name="max_capacity" value="{{ old('max_capacity', $room->max_capacity ?? 2) }}" required>
        </div>
    </div>
    <div>
        <label class="field-label"><x-icon name="info" size="sm" /> Status</label>
        <select class="input" name="status">
            <option value="available" @selected(old('status', $room->status ?? 'available') === 'available')>Available</option>
            <option value="maintenance" @selected(old('status', $room->status ?? '') === 'maintenance')>Maintenance</option>
        </select>
    </div>
    <div>
        <label class="field-label"><x-icon name="edit" size="sm" /> Notes</label>
        <textarea class="input" name="notes" rows="3">{{ old('notes', $room->notes ?? '') }}</textarea>
    </div>
    <button class="btn btn-primary"><x-icon name="check" size="sm" /> Save room</button>
</form>

<form method="POST" action="{{ $action }}" class="card mx-auto max-w-xl space-y-4 p-6">
    @csrf
    @if($method !== 'POST')
        @method($method)
    @endif
    <x-section-title icon="user" hint="Saved guests can be searched at booking">Customer</x-section-title>
    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label class="field-label"><x-icon name="user" size="sm" /> Name</label>
            <input class="input" name="name" value="{{ old('name', $customer->name ?? '') }}" required>
        </div>
        <div>
            <label class="field-label"><x-icon name="users" size="sm" /> Father name</label>
            <input class="input" name="father_name" value="{{ old('father_name', $customer->father_name ?? '') }}">
        </div>
    </div>
    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label class="field-label"><x-icon name="phone" size="sm" /> Phone</label>
            <input class="input" name="phone" value="{{ old('phone', $customer->phone ?? '') }}" required>
        </div>
        <div>
            <label class="field-label"><x-icon name="id" size="sm" /> ID card number</label>
            <input class="input" name="cnic" value="{{ old('cnic', $customer->cnic ?? '') }}">
        </div>
    </div>
    <div>
        <label class="field-label"><x-icon name="map" size="sm" /> Address</label>
        <textarea class="input" name="address" rows="3">{{ old('address', $customer->address ?? '') }}</textarea>
    </div>
    <button class="btn btn-primary"><x-icon name="check" size="sm" /> Save customer</button>
</form>

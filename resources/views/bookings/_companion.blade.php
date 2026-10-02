@php
    $row = $row ?? [];
@endphp
<div class="rounded-2xl border border-line bg-white p-4" data-companion-row>
    <input type="hidden" name="companions[{{ $index }}][id]" value="{{ $row['id'] ?? '' }}">
    <div class="mb-3 flex items-center justify-between">
        <div class="inline-flex items-center gap-2 text-sm font-semibold">
            <x-icon-box size="sm" tone="blue"><x-icon name="user" size="sm" /></x-icon-box>
            Extra guest
        </div>
        <button type="button" class="inline-flex items-center gap-1 text-xs font-medium text-crimson" data-remove-companion>
            <x-icon name="x" size="sm" /> Remove
        </button>
    </div>
    <div class="grid gap-3 sm:grid-cols-2">
        <div>
            <label class="field-label"><x-icon name="user" size="sm" /> Name</label>
            <input class="input" name="companions[{{ $index }}][name]" value="{{ $row['name'] ?? '' }}" placeholder="Full name">
        </div>
        <div>
            <label class="field-label"><x-icon name="user" size="sm" /> Father name / Husband name</label>
            <input class="input" name="companions[{{ $index }}][father_name]" value="{{ $row['father_name'] ?? '' }}" placeholder="Father or husband name">
        </div>
        <div>
            <label class="field-label"><x-icon name="id" size="sm" /> CNIC</label>
            <input class="input" name="companions[{{ $index }}][cnic]" value="{{ $row['cnic'] ?? '' }}" placeholder="12345-1234567-1">
        </div>
        <div class="sm:col-span-2">
            <label class="field-label"><x-icon name="map" size="sm" /> Address</label>
            <textarea class="input" name="companions[{{ $index }}][address]" rows="2" placeholder="Street address, city, etc.">{{ $row['address'] ?? '' }}</textarea>
        </div>
        <div>
            <label class="field-label"><x-icon name="image" size="sm" /> ID front</label>
            <label class="file-drop min-h-24">
                <x-icon name="camera" size="sm" />
                <span class="text-xs">Optional photo</span>
                <input type="file" name="companions[{{ $index }}][id_card_front]" accept="image/*">
            </label>
            @if(!empty($row['front']))
                <a class="mt-1 inline-block text-xs font-medium text-crimson" href="{{ $row['front'] }}" target="_blank">View current</a>
            @endif
        </div>
        <div>
            <label class="field-label"><x-icon name="image" size="sm" /> ID back</label>
            <label class="file-drop min-h-24">
                <x-icon name="camera" size="sm" />
                <span class="text-xs">Optional photo</span>
                <input type="file" name="companions[{{ $index }}][id_card_back]" accept="image/*">
            </label>
            @if(!empty($row['back']))
                <a class="mt-1 inline-block text-xs font-medium text-crimson" href="{{ $row['back'] }}" target="_blank">View current</a>
            @endif
        </div>
    </div>
</div>

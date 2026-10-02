<div>
    <label class="field-label"><x-icon name="user" size="sm" /> Name</label>
    <input class="input" name="name" value="{{ old('name') }}" placeholder="Full name" required>
</div>
<div class="grid gap-4 sm:grid-cols-2">
    <div>
        <label class="field-label"><x-icon name="phone" size="sm" /> Phone</label>
        <input class="input" name="phone" value="{{ old('phone') }}" placeholder="03xx-xxxxxxx">
    </div>
    <div>
        <label class="field-label"><x-icon name="briefcase" size="sm" /> Position</label>
        <input class="input" name="position" value="{{ old('position') }}" list="employee-positions" placeholder="Housekeeping, Chef…">
    </div>
</div>
<div class="grid gap-4 sm:grid-cols-2">
    <div>
        <label class="field-label"><x-icon name="money" size="sm" /> Monthly salary</label>
        <input class="input text-lg font-semibold" type="number" min="0" step="0.01" name="salary" value="{{ old('salary') }}" placeholder="0" required>
    </div>
    <div>
        <label class="field-label"><x-icon name="calendar" size="sm" /> Hire date</label>
        <input class="input" type="date" name="hire_date" value="{{ old('hire_date', now()->toDateString()) }}" max="{{ now()->toDateString() }}">
    </div>
</div>
<div>
    <label class="field-label"><x-icon name="staff" size="sm" /> Status</label>
    <select class="input" name="status" required>
        <option value="active" @selected(old('status', 'active') === 'active')>Active</option>
        <option value="inactive" @selected(old('status') === 'inactive')>Inactive</option>
    </select>
</div>
<div>
    <label class="field-label"><x-icon name="edit" size="sm" /> Notes</label>
    <textarea class="input" name="notes" rows="2" placeholder="Optional">{{ old('notes') }}</textarea>
</div>

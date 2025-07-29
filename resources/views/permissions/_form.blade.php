<div class="mb-3">
    <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
    <input type="text" name="name" id="name" value="{{ old('name', $permission->name ?? '') }}"
        class="mt-1 form-input p-2" @if ($mode == 'edit') {{ 'disabled' }} @endif />
    <div class="text-gray-500 text-xs">#Note: Permission name if once created, cannot be changed forever.</div>
    @error('name')
        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
    @enderror
</div>

<div class="mb-3">
    <label for="label" class="block text-sm font-medium text-gray-700">Label</label>
    <input type="text" name="label" id="label" value="{{ old('label', $permission->label ?? '') }}"
        class="mt-1 form-input p-2">
    @error('label')
        <p class="text-sm text-red-600 mt-1">{{ $errors->first('label') }}</p>
    @enderror
</div>

<div class="mb-3">
    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
    <textarea name="description" id="description" class="mt-1 form-input p-2">{{ old('description', $permission->description ?? '') }}</textarea>
    @error('description')
        <p class="text-sm text-red-600 mt-1">{{ $errors->first('description') }}</p>
    @enderror
</div>

<div class="mb-3">
    <label for="group" class="block text-sm font-medium text-gray-700">Group</label>
    <input type="text" name="group" id="group" value="{{ old('group', $permission->group ?? '') }}"
        class="mt-1 form-input p-2">
    @error('group')
        <p class="text-sm text-red-600 mt-1">{{ $errors->first('group') }}</p>
    @enderror
</div>

{{-- <div class="mb-4">
    <label for="enabled" class="inline-flex items-center">
        <input type="checkbox" name="enabled" id="enabled"
            {{ old('enabled', $permission->enabled ?? false) ? 'checked' : '' }} class="form-checkbox">
        <span class="ml-2">Enabled</span>
    </label>
</div> --}}

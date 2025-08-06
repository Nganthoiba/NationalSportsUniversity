@props(['sport' => null, 'action', 'method' => 'POST'])

<form action="{{ $action }}" method="POST" class="space-y-6">
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif

    <div>
        <label for="sport_name" class="block text-sm font-medium text-gray-700">Sport Name (English) <span
                class="text-red-600">*</span></label>
        <input type="text" name="sport_name" id="sport_name" value="{{ old('sport_name', $sport->sport_name ?? '') }}"
            required
            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
        @error('sport_name')
            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="mt-2">
        <label for="sport_name_in_hindi" class="block text-sm font-medium text-gray-700">Sport Name (Hindi)</label>
        <input type="text" name="sport_name_in_hindi" id="sport_name_in_hindi"
            value="{{ old('sport_name_in_hindi', $sport->sport_name_in_hindi ?? '') }}"
            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
        @error('sport_name_in_hindi')
            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="flex justify-end space-x-4 gap-2 mt-2">
        <a href="{{ route('sports.index') }}"
            class="inline-flex items-center px-4 py-2 text-sm text-gray-700 border border-gray-300 rounded-md hover:bg-gray-100">
            Cancel
        </a>
        <button type="submit"
            class="inline-flex items-center px-4 py-2 text-sm text-white bg-blue-600 hover:bg-blue-700 rounded-md shadow">
            Save
        </button>
    </div>
</form>

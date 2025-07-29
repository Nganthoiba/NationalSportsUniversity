@extends('layout.app')
@section('content')
    <div class="max-w-lg mx-auto p-6 bg-white shadow-lg rounded-lg">
        <h2 class="text-2xl font-semibold text-gray-800 mb-6">User Settings</h2>

        <!-- Profile Settings Section -->
        <div class="mb-6">
            <h5 class="text-xl font-semibold text-gray-700 mb-4">Profile Settings</h5>

            <div class="space-y-2">
                <p class="text-gray-600"><span class="font-semibold">Full Name:</span> {{ $user->full_name }}</p>
                <p class="text-gray-600"><span class="font-semibold">Email:</span> {{ $user->email }}</p>
                <p class="text-gray-600"><span class="font-semibold">Role(s) assigned to you:</span>
                    {{ implode(',', $user->getAssignedRoles()) }}</p>
            </div>

            <a href="/profile/edit" class="mt-4 inline-block bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                Update Profile
            </a>
        </div>

        <!-- Account & Security Section -->
        <div>
            <h3 class="text-xl font-semibold text-gray-700 mb-4">Account & Security</h3>

            <a href="{{ route('setting.changePassword') }}"
                class="inline-block bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600">
                Update Password
            </a>
        </div>
    </div>
@endsection

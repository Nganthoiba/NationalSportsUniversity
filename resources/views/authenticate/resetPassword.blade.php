@extends('layout.app')
@section('content')
    <div class="flex flex-col w-full lg:w-1/3 lg:pr-8 mx-auto mt-2 bg-white rounded-lg p-6">
        <h1 class="text-2xl font-bold mb-4">Reset Your Password</h1>

        <!-- Display Success or Error Messages -->
        @if (session('success'))
            <div class="relative mb-4 p-4 text-sm text-green-700 bg-green-100 border border-green-300 rounded-lg">
                {{ session('success') }}
                <button type="button" onclick="this.parentElement.remove()"
                    class="absolute top-1 right-2 text-green-700 hover:text-green-900 hover:cursor-pointer">
                    x
                </button>
            </div>
        @endif

        @if (session('error'))
            <div class="relative mb-4 p-4 text-sm text-red-700 bg-red-100 border border-red-300 rounded-lg">
                {{ session('error') }}
                <button type="button" onclick="this.parentElement.remove()"
                    class="absolute top-1 right-2 text-red-700 hover:text-red-900 hover:cursor-pointer">
                    &times;
                </button>
            </div>
        @endif
        <form method="POST" action="{{ route('updatePassword') }}" class="flex flex-col space-y-4">
            @csrf

            <input type="hidden" value="{{ $email }}" name="email" />
            <input type="hidden" value="{{ $token }}" name="token" />

            <div class="flex flex-col space-y-1">
                <label for="new_password" class="text-sm font-medium">New Password</label>
                <input id="new_password" type="password" name="new_password" required autocomplete="current-password"
                    class="rounded-lg border border-gray-300 focus:border-gray-500 focus:ring-1 focus:ring-gray-500 focus:outline-none p-2">
                <div class="error text-sm">
                    @if ($errors->has('new_password'))
                        {{ $errors->first('new_password') }}
                    @endif
                </div>
            </div>

            <div class="flex flex-col space-y-1">
                <label for="confirm_new_password" class="text-sm font-medium">Confirm New Password</label>
                <input id="confirm_new_password" type="password" name="confirm_new_password" required
                    autocomplete="current-password"
                    class="rounded-lg border border-gray-300 focus:border-gray-500 focus:ring-1 focus:ring-gray-500 focus:outline-none p-2">
                <div class="error text-sm">
                    @if ($errors->has('confirm_new_password'))
                        {{ $errors->first('confirm_new_password') }}
                    @endif
                </div>
            </div>

            <div class="mt-4">
                <button type="submit"
                    class="bg-gray-800 text-white rounded-lg py-2 transition-colors duration-200 hover:bg-gray-700
                     w-full 
                    cursor-pointer">Change
                    Password</button>
            </div>

            @if ($errors->any())
                {{-- 
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>
                            <div class="relative mb-4 p-4 text-sm text-red-700 bg-red-100 border border-red-300 rounded-lg">
                                {{ $error }}
                                <button type="button" onclick="this.parentElement.remove()"
                                    class="absolute top-1 right-2 text-red-700 hover:text-red-900 hover:cursor-pointer">
                                    &times;
                                </button>
                            </div>
                        </li>
                    @endforeach
                </ul> --}}
            @endif

        </form>
    </div>
@endsection

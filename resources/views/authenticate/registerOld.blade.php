@extends('layout.app')
@section('content')
    <div class="flex flex-col w-full lg:w-1/3 lg:pr-8 mx-auto shadow-lg rounded-lg bg-white p-6 space-y-4">
        <h1 class="text-2xl font-bold mb-4">Register below</h1>
        <form method="POST" action="{{ route('register') }}" class="flex flex-col space-y-4">
            @csrf
            <div class="flex flex-col space-y-1">
                <label for="name" class="text-sm font-medium">Full Name</label>
                <input id="name" type="text" name="full_name" value="{{ old('full_name') }}" required
                    autocomplete="full_name" autofocus
                    class="rounded-lg border border-gray-300 focus:border-gray-500 focus:ring-1 focus:ring-gray-500 focus:outline-none p-2">
                <div class="error">
                    @if ($errors->has('full_name'))
                        {{ $errors->first('full_name') }}
                    @endif
                </div>
            </div>
            <div class="flex flex-col space-y-1">
                <label for="name" class="text-sm font-medium">Contact Number</label>
                <input id="name" type="number" maximum="10" name="contact_no" value="{{ old('contact_no') }}"
                    required autocomplete="contact_no" autofocus
                    class="rounded-lg border border-gray-300 focus:border-gray-500 focus:ring-1 focus:ring-gray-500 focus:outline-none p-2">
                <div class="error">
                    @if ($errors->has('contact_no'))
                        {{ $errors->first('contact_no') }}
                    @endif
                </div>
            </div>
            <div class="flex flex-col space-y-1">
                <label for="email" class="text-sm font-medium">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required
                    autocomplete="email"
                    class="rounded-lg border border-gray-300 focus:border-gray-500 focus:ring-1 focus:ring-gray-500 focus:outline-none p-2">
                <div class="error">
                    @if ($errors->has('email'))
                        {{ $errors->first('email') }}
                    @endif
                </div>
            </div>
            <div class="flex flex-col space-y-1">
                <label for="password" class="text-sm font-medium">Password</label>
                <input id="password" type="password" name="password" required autocomplete="new-password"
                    class="rounded-lg border border-gray-300 focus:border-gray-500 focus:ring-1 focus:ring-gray-500 focus:outline-none p-2">
                <div class="error">
                    @if ($errors->has('password'))
                        {{ $errors->first('password') }}
                    @endif
                </div>
            </div>
            <div class="flex flex-col space-y-1">
                <label for="password_confirmation" class="text-sm font-medium">Confirm Password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required
                    autocomplete="new-password"
                    class="rounded-lg border border-gray-300 focus:border-gray-500 focus:ring-1 focus:ring-gray-500 focus:outline-none p-2">

                <div class="error">
                    @if ($errors->has('password_confirmation'))
                        {{ $errors->first('password_confirmation') }}
                    @endif
                </div>
            </div>
            <button type="submit"
                class="bg-gray-800 text-white rounded-lg py-2 transition-colors duration-200 hover:bg-gray-700">Register</button>

        </form>
    </div>
@endsection

@extends('layout.app')
@section('content')
    <div class="container">
        <div class="w-1/3 mx-auto">
            @include('layout.server_response')
        </div>
        <div class="w-2/3 bg-white shadow-lg rounded-lg py-4 px-6 mx-auto">
            <h5 class="text-gray-600 text-lg font-bold border-b border-gray-200 py-1 mb-3">Create University Admin User</h5>

            <form action="{{ route('users.create') }}" method="POST">
                <input type="hidden" value="{{ $role->id }}" name="role_id" />
                @csrf
                <div class="mb-4">
                    <label for="full_name" class="py-2">User Name (in full):</label>
                    <div>
                        <input type="text" value="{{ old('full_name', '') }}" class="form-input px-3 py-2"
                            name="full_name" id="full_name" required>
                        @if ($errors->has('full_name'))
                            <span class="text-red-500 text-sm mt-1">{{ $errors->first('full_name') }}</span>
                        @endif
                    </div>
                </div>
                <div class="mb-4">
                    <label for="email" class="py-2">Email:</label>
                    <div>
                        <input type="email" class="form-input px-3 py-2" id="email" name="email" required>
                        @if ($errors->has('email'))
                            <span class="text-red-500 text-sm mt-1">{{ $errors->first('email') }}</span>
                        @endif
                    </div>
                </div>
                <div class="mb-4">
                    <label for="contact_no" class="py-2">Contact Number:</label>
                    <div>
                        <input type="number" class="form-input px-3 py-2" name="contact_no" id="contact_no" required>
                        @if ($errors->has('contact_no'))
                            <span class="text-red-500 text-sm mt-1">{{ $errors->first('contact_no') }}</span>
                        @endif
                    </div>
                </div>

                <div class="mb-4">
                    <label for="designation" class="py-2">Designation:</label>
                    <div>
                        <input type="text" class="form-input px-3 py-2" id="designation" name="designation" required>
                        @if ($errors->has('designation'))
                            <span class="text-red-500 text-sm mt-1">{{ $errors->first('designation') }}</span>
                        @endif
                    </div>
                </div>

                <div class="mb-4">
                    <label for="place_of_posting" class="py-2">Place of posting:</label>
                    <div>
                        <input type="text" class="form-input px-3 py-2" id="place_of_posting" name="place_of_posting"
                            required>
                        @if ($errors->has('place_of_posting'))
                            <span class="text-red-500 text-sm mt-1">{{ $errors->first('place_of_posting') }}</span>
                        @endif
                    </div>
                </div>

                <div class="mb-4">
                    <label for="contact_no" class="py-2">Select University:</label>
                    <div>
                        {{-- json_encode($universities) --}}
                        <select name="university_id" id="university_id" class="form-select" required>
                            <option value="">Select University</option>
                            @foreach ($universities as $university)
                                @php
                                    $selected = old('university_id', '') == $university->id ? 'selected' : '';
                                @endphp
                                <option value="{{ $university->id }}" {{ $selected }}>{{ $university->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="mt-4 flex justify-between">
                    <div class="text-gray-500 text-sm">
                        After the user is created, an email containing an activation link will be sent to the registered
                        email address. The user must click the link to visit the site, set a password, and activate their
                        account.
                    </div>
                    <div><button class="btn btn-primary">Create</button></div>
                </div>
            </form>
        </div>
    </div>
@endsection

@extends('layout.app')
@section('content')
    <div class="w-1/2 mx-auto">
        @include('layout.server_response')
    </div>
    <div class="flex flex-col w-full lg:w-1/3 lg:pr-8 mx-auto shadow-lg rounded-lg bg-white p-6 space-y-4">
        @if (session('otp_initiated'))
            {{-- session('otp_initiated') --}}
            <h1 class="text-2xl font-bold mb-4">Setup your password below</h1>
            <div>
                <form name="setup_password_form" method="POST" action="{{ route('verifyOTPandUpdatePassword') }}"
                    class="flex flex-col space-y-4">
                    @csrf

                    <input type="hidden" name="otp_id" value="{{ old('otp_id', session('otp_id')) }}" />

                    <div class="flex flex-col space-y-1">
                        <label for="otp" class="text-sm font-medium">Please enter the OTP which you receive in
                            email. Your OTP ID is <span
                                id="span_otp_id">{{ old('otp_id', session('otp_id')) }}</span></label>
                        <input id="otp" type="text" name="otp" required autocomplete="current-password"
                            placeholder="Enter OTP"
                            class="rounded-lg border border-gray-300 focus:border-gray-500 focus:ring-1 focus:ring-gray-500 focus:outline-none p-2">
                        <div class="error text-sm">
                            @if ($errors->has('otp'))
                                {{ $errors->first('otp') }}
                            @endif
                        </div>
                        <div class="error text-sm">
                            @if ($errors->has('otp_id'))
                                {{ $errors->first('otp_id') }}
                            @endif
                        </div>
                        <div class="flex justify-between text-gray-600 text-sm">
                            <input type="hidden" name="email" value="{{ old('email') }}" />
                            <div>OTP expired or not received?</div>
                            <div>
                                <button class="btn btn-info flex items-center gap-2" type="button" id="resendOtpBtn">
                                    <span
                                        class="loader hidden border-2 border-t-transparent border-blue-500 rounded-full animate-spin"></span>
                                    <span class="label">Resend OTP</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col space-y-1">
                        <label for="new_password" class="text-sm font-medium">New Password</label>
                        <input id="new_password" type="password" name="new_password" required
                            autocomplete="current-password"
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
                </form>
            </div>
        @else
            <h1 class="text-2xl font-bold mb-4">Complete Registration below</h1>
            <div class="text-sm text-gray-500">
                To complete your registration, your email must first be registered by your designated administrator. Then
                you
                must request the application to send one time password(OTP) to your registered email ID.
            </div>
            <form method="POST" name="sendOTPForm" action="{{ route('sendOTPLink') }}" class="flex flex-col space-y-4">
                @csrf
                <div class="flex flex-col space-y-1">
                    <label for="email" class="text-sm font-medium">Enter your registered email</label>
                    <input id="email" type="email" name="email" value="{{ old('email', $email) }}" required
                        autocomplete="email" autofocus
                        class="rounded-lg border border-gray-300 focus:border-gray-500 focus:ring-1 focus:ring-gray-500 focus:outline-none p-2">
                    <div class="error text-sm">
                        @if ($errors->has('email'))
                            {{ $errors->first('email') }}
                        @endif
                    </div>
                </div>
                <div class="mt-4">
                    <button type="submit"
                        class="bg-gray-800 text-white rounded-lg py-2 
                    transition-colors duration-200 hover:bg-gray-700 w-full 
                    cursor-pointer gap-5 flex items-center justify-center">
                        <span>Send OTP</span>
                    </button>
                </div>
                <div class="hidden" id="loader">
                    <div class="flex justify-center items-center gap-2">
                        <div class="w-8 h-8 border-4 border-blue-500 border-t-transparent rounded-full animate-spin"></div>
                        <div class="text-gray-500">Sending OTP ...</div>
                    </div>
                </div>
            </form>
        @endif
        @if ($errors->any())
            {{-- <ul>
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
    </div>
@endsection
@section('javascripts')
    <script>
        const sendOTPForm = document.forms['sendOTPForm'];
        if (sendOTPForm) {
            sendOTPForm.onsubmit = function(e) {
                e.preventDefault();
                document.getElementById("loader").classList.remove("hidden");
                this.submit();
            };
        }


        const resendOtpBtn = document.getElementById("resendOtpBtn");
        if (resendOtpBtn) {
            resendOtpBtn.addEventListener("click", (e) => {
                resendOtpBtn.querySelector("span.loader").classList.remove("hidden");
                resendOtpBtn.querySelector("span.label").innerHTML = "Resending ...";
                resendOtpBtn.disabled = true;
                const csrfToken = "{{ csrf_token() }}";

                fetch("{{ route('sendOTPLink') }}", {
                        method: "POST",
                        headers: {
                            "Accept": "application/json",
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": csrfToken
                        },
                        body: JSON.stringify({
                            'email': document.forms['setup_password_form'].email.value
                        })

                    }).then(response => {
                        if (!response.ok) {
                            return response.json().then(err => Promise.reject(err));
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('Success:', data);
                        alert(data.message);
                        document.forms['setup_password_form'].otp_id.value = data.otp_id;
                        document.getElementById("span_otp_id").innerHTML = data.otp_id;

                        resendOtpBtn.querySelector("span.loader").classList.add("hidden");
                        resendOtpBtn.querySelector("span.label").innerHTML = "Resend OTP";
                        resendOtpBtn.disabled = false;
                    })
                    .catch(error => {
                        console.error('Validation error:', error.errors);
                        alert("An error happens while generating OTP.");
                        resendOtpBtn.querySelector("span.loader").classList.add("hidden");
                        resendOtpBtn.querySelector("span.label").innerHTML = "Resend OTP";
                        resendOtpBtn.disabled = false;
                    });
            })
        }
    </script>
@endsection

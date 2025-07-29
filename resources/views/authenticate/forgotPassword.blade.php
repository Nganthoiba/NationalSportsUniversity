@extends('layout.app')
@section('content')
    <div
        class="flex flex-col w-full lg:w-1/3 lg:pr-8 mx-auto mt-2 shadow-lg rounded-lg
                     border-gray-300 bg-white
                     p-6 space-y-4
                     ">
        <h1 class="text-2xl font-bold mb-4">Forgot Password ...?</h1>
        @include('authenticate._password_reset_form');
    </div>
@endsection

@section('javascripts')
    <script>
        document.forms['sendPasswordResetLinkForm'].onsubmit = function(event) {
            event.preventDefault();
            document.querySelector("#loader").classList.remove("hidden");
            this.submit();
        };
    </script>
@endsection

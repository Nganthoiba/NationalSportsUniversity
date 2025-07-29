<form method="POST" name="sendPasswordResetLinkForm" action="{{ route('sendPasswordResetLink') }}"
    class="flex flex-col space-y-4">
    @csrf
    <div class="flex flex-col space-y-1">
        <label for="email" class="text-sm font-medium">Enter your registered email</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email"
            autofocus
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
            <span>Send Password Reset Link</span>
        </button>
    </div>

    <div class="flex justify-center items-center hidden" id="loader">
        <div class="w-8 h-8 border-4 border-blue-500 border-t-transparent rounded-full animate-spin"></div>
    </div>

    @if (session('error'))
        <div class="relative mb-4 p-3 rounded bg-red-100 text-red-700 border border-red-300">
            <button type="button" onclick="this.parentElement.remove()"
                class="absolute top-1 right-1 text-red-700 hover:text-red-900 hover:cursor-pointer">
                x
            </button>
            {{ session('error') }}
        </div>
    @endif

    @if (session('success'))
        <div class="relative mb-4 p-3 rounded bg-green-100 text-green-700 border border-green-300">
            <button type="button" onclick="this.parentElement.remove()"
                class="absolute top-1 right-1 text-green-700 hover:text-green-900 hover:cursor-pointer">
                x
            </button>
            {{ session('success') }}
        </div>
    @endif

</form>

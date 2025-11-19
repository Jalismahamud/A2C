<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" id="loginForm" action="{{ route('admin.login') }}">
        @csrf

        <!-- Phone Number -->
        <div>
            <x-input-label for="phone" :value="__('Phone')" />
            <x-text-input id="phone" class="block mt-1 w-full" type="number" name="phone" :value="old('phone')" required
                autofocus autocomplete="phone" />
            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
        </div>

        <!-- Password with show/hide icon inside input -->
        <!-- Password with show/hide icon inside input -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <div style="position: relative;">
                <!-- Add padding-right to make space for icon -->
                <x-text-input id="password" type="password" name="password" class="block mt-1 w-full"
                    style="padding-right: 40px;" required autocomplete="current-password" />

                <!-- Eye Icon inside input field -->
                <button type="button" onclick="togglePassword()"
                    style="position: absolute; top: 50%; right: 10px; transform: translateY(-50%); background: transparent; border: none; padding: 0; cursor: pointer;"
                    tabindex="-1">
                    <svg style="color: gray;" id="eyeIcon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12
                        5c4.477 0 8.268 2.943 9.542 7-1.274
                        4.057-5.065 7-9.542 7-4.477
                        0-8.268-2.943-9.542-7z" />
                    </svg>
                </button>
            </div>

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox"
                    class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800"
                    name="remember">
                <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
            </label>
        </div>

        <!-- Buttons -->
        <div class="flex items-center justify-end mt-4">
            <!--@if (Route::has('password.request'))-->
            <!--    <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"-->
            <!--        href="{{ route('password.request') }}">-->
            <!--        {{ __('Forgot your password?') }}-->
            <!--    </a>-->
            <!--@endif-->

            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.innerHTML = `
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478
                    0-8.27-2.944-9.544-7a10.05 10.05 0
                    013.186-4.507m3.354-1.528A9.953 9.953
                    0 0112 5c4.477 0 8.268 2.943
                    9.542 7a9.964 9.964 0 01-4.228
                    5.303M15 12a3 3 0 11-6 0 3 3 0
                    016 0zM3 3l18 18" />
            `;
            } else {
                passwordInput.type = 'password';
                eyeIcon.innerHTML = `
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M2.458 12C3.732 7.943 7.523 5
                    12 5c4.477 0 8.268 2.943
                    9.542 7-1.274 4.057-5.065
                    7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
            `;
            }
        }
    </script>

    <!-- ✅ JavaScript to Check Role by Phone -->

    <script>
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const phone = document.getElementById('phone').value;
            const form = document.getElementById('loginForm');

            const checkUrl = @json(route('check.role'));

            const adminLoginRoute = @json(route('admin.login'));
            const agentLoginRoute = @json(route('agent-or-incharge.login'));

            fetch(checkUrl + '?phone=' + encodeURIComponent(phone))
                .then(response => {
                    if (!response.ok) {
                        throw new Error('User not found');
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('User role:', data.role);
                    form.action = (data.role == 1) ? adminLoginRoute : agentLoginRoute;
                    form.submit();
                })
                .catch(error => {
                    alert(error.message || 'User not found or error occurred.');
                });
        });
    </script>

</x-guest-layout>




{{-- <x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('admin.login') }}">
        @csrf

        <!--Phone Number -->
        <div>
            <x-input-label for="phone" :value="__('Phone')" />
            <x-text-input id="phone" class="block mt-1 w-full" type="number" name="phone" :value="old('phone')" required
                autofocus autocomplete="phone" />
            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required
                autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox"
                    class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800"
                    name="remember">
                <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                    href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout> --}}

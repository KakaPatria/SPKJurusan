<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        {{ __('Ini adalah area aman aplikasi. Silakan konfirmasi password Anda sebelum melanjutkan.') }}
    </div>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" />

            <div style="position: relative; display: flex; align-items: center;">
                <input id="password" class="block mt-1 w-full border border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500" type="password" name="password" required autocomplete="current-password" placeholder="Enter password" style="padding-right: 45px;" />
                <button type="button" style="position: absolute; right: 12px; background: none; border: none; cursor: pointer; color: #5B7B89; font-size: 18px;" onclick="togglePasswordVisibility('password', this)">👁️</button>
            </div>

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex justify-end mt-4">
            <x-primary-button>
                {{ __('Konfirmasi') }}
            </x-primary-button>
        </div>
    </form>

    @push('scripts')
        <script>
            function togglePasswordVisibility(inputId, buttonElement) {
                const input = document.getElementById(inputId);
                const isPassword = input.type === 'password';
                input.type = isPassword ? 'text' : 'password';
            }
        </script>
    @endpush
</x-guest-layout>

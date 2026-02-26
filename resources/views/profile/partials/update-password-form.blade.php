<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Perbarui Password') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Pastikan akun Anda menggunakan password yang panjang dan acak untuk tetap aman.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <div>
            <x-input-label for="update_password_current_password" :value="__('Current Password')" />
            <div style="position: relative; display: flex; align-items: center;">
                <input id="update_password_current_password" name="current_password" type="password" class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500" autocomplete="current-password" placeholder="Enter current password" style="padding-right: 45px;" />
                <button type=\"button\" style=\"position: absolute; right: 12px; background: none; border: none; cursor: pointer; color: #5B7B89; font-size: 18px; margin-top: 5px;\" onclick=\"togglePasswordVisibility('update_password_current_password', this)\">👁️</button>
            </div>
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="update_password_password" :value="__('New Password')" />
            <div style="position: relative; display: flex; align-items: center;">
                <input id="update_password_password" name="password" type="password" class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500" autocomplete="new-password" placeholder="Enter new password" style="padding-right: 45px;" />
                <button type=\"button\" style=\"position: absolute; right: 12px; background: none; border: none; cursor: pointer; color: #5B7B89; font-size: 18px; margin-top: 5px;\" onclick=\"togglePasswordVisibility('update_password_password', this)\">👁️</button>
            </div>
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="update_password_password_confirmation" :value="__('Confirm Password')" />
            <div style="position: relative; display: flex; align-items: center;">
                <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500" autocomplete="new-password" placeholder="Confirm password" style="padding-right: 45px;" />
                <button type=\"button\" style=\"position: absolute; right: 12px; background: none; border: none; cursor: pointer; color: #5B7B89; font-size: 18px; margin-top: 5px;\" onclick=\"togglePasswordVisibility('update_password_password_confirmation', this)\">👁️</button>
            </div>
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>

@push('scripts')
<script>
    function togglePasswordVisibility(inputId, buttonElement) {
        const input = document.getElementById(inputId);
        const isPassword = input.type === 'password';
        input.type = isPassword ? 'text' : 'password';
    }
</script>
@endpush

<x-guest-layout>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <form method="POST" action="{{ route('password.confirm') }}" id="confirm-password-form">
        @csrf
        <input type="hidden" name="password" id="password-input">
    </form>

    <div class="mb-4 text-sm text-gray-600">
        {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
    </div>

    <p class="text-sm text-gray-500">{{ __('Mengalihkan ke dialog konfirmasi...') }}</p>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        let password = '';

        @if ($errors->any())
        Swal.fire({
            title: '{{ __("Konfirmasi Kata Sandi") }}',
            html: '<input id="swal-password" type="password" class="swal2-input" placeholder="{{ __("Password") }}" autocomplete="current-password">'
                + '<p class="text-red-500 text-sm mt-2">{{ $errors->first("password") }}</p>',
            confirmButtonText: '{{ __("Confirm") }}',
            showCancelButton: true,
            cancelButtonText: '{{ __("Batal") }}',
            focusConfirm: false,
            allowOutsideClick: false,
            preConfirm: () => {
                return document.getElementById('swal-password').value;
            }
        }).then((result) => {
            if (result.isConfirmed && result.value) {
                document.getElementById('password-input').value = result.value;
                document.getElementById('confirm-password-form').submit();
            } else if (result.isDismissed) {
                window.location.href = '/';
            }
        });
        @else
        Swal.fire({
            title: '{{ __("Konfirmasi Kata Sandi") }}',
            html: '<input id="swal-password" type="password" class="swal2-input" placeholder="{{ __("Password") }}" autocomplete="current-password">',
            confirmButtonText: '{{ __("Confirm") }}',
            showCancelButton: true,
            cancelButtonText: '{{ __("Batal") }}',
            focusConfirm: false,
            allowOutsideClick: false,
            preConfirm: () => {
                return document.getElementById('swal-password').value;
            }
        }).then((result) => {
            if (result.isConfirmed && result.value) {
                document.getElementById('password-input').value = result.value;
                document.getElementById('confirm-password-form').submit();
            } else if (result.isDismissed) {
                window.location.href = '/';
            }
        });
        @endif
    });
    </script>
</x-guest-layout>

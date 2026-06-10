<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Delete Account') }}
        </h2>
        <p class="mt-1 text-sm text-gray-600">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </p>
    </header>

    <form method="post" action="{{ route('profile.destroy') }}" id="delete-account-form">
        @csrf
        @method('delete')
        <input type="hidden" name="password" id="delete-account-password">
    </form>

    <button type="button" onclick="confirmDeleteAccount()"
        class="px-4 py-2 bg-error text-on-error rounded-lg font-label-md hover:bg-error/90 transition-colors">
        {{ __('Delete Account') }}
    </button>

    @push('scripts')
    <script>
    function confirmDeleteAccount() {
        Swal.fire({
            title: '{{ __("Apakah Anda yakin?") }}',
            text: '{{ __("Setelah akun dihapus, semua data akan terhapus permanen. Masukkan kata sandi untuk konfirmasi.") }}',
            icon: 'warning',
            input: 'password',
            inputPlaceholder: '{{ __("Kata Sandi") }}',
            inputAttributes: {
                autocomplete: 'current-password'
            },
            showCancelButton: true,
            confirmButtonText: '{{ __("Ya, hapus akun!") }}',
            cancelButtonText: '{{ __("Batal") }}',
            confirmButtonColor: '#dc2626',
            reverseButtons: true,
            preConfirm: (password) => {
                if (!password) {
                    Swal.showValidationMessage('{{ __("Kata sandi wajib diisi") }}');
                }
                return password;
            }
        }).then((result) => {
            if (result.isConfirmed && result.value) {
                document.getElementById('delete-account-password').value = result.value;
                document.getElementById('delete-account-form').submit();
            }
        });
    }
    </script>
    @endpush
</section>

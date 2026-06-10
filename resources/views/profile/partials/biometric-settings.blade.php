<div x-data="biometricManager()" x-init="init()" class="bg-white rounded-xl border border-outline-variant card-shadow p-6">
    <h3 class="font-headline-sm text-headline-sm text-on-surface mb-1">Pengaturan Biometric</h3>
    <p class="text-body-sm text-on-surface-variant mb-6">Kelola perangkat biometric untuk login cepat tanpa password</p>

    <template x-if="loading">
        <div class="flex items-center justify-center py-8">
            <span class="material-symbols-outlined animate-spin text-primary">progress_activity</span>
            <span class="ml-3 text-body-sm text-on-surface-variant">Memuat data...</span>
        </div>
    </template>

    <template x-if="!loading && credentials.length === 0">
        <div class="text-center py-8 border-2 border-dashed border-outline-variant rounded-xl">
            <span class="material-symbols-outlined text-4xl text-on-surface-variant mb-2">fingerprint</span>
            <p class="text-body-sm text-on-surface-variant mb-4">Belum ada perangkat biometric terdaftar</p>
            <button @click="registerBiometric()" class="inline-flex items-center gap-1.5 px-6 py-2.5 bg-primary text-on-primary rounded-lg font-label-md hover:bg-primary/90 transition-all">
                <span class="material-symbols-outlined text-lg">add</span>
                Tambah Biometric
            </button>
        </div>
    </template>

    <template x-if="!loading && credentials.length > 0">
        <div class="space-y-3">
            <template x-for="cred in credentials" :key="cred.id">
                <div class="flex items-center justify-between p-4 bg-surface-container-low rounded-xl">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-2xl text-secondary">fingerprint</span>
                        <div>
                            <p class="font-label-md text-label-md text-on-surface" x-text="cred.name"></p>
                            <p class="text-body-sm text-on-surface-variant" x-text="'Terdaftar ' + cred.created_at"></p>
                        </div>
                    </div>
                    <button @click="deleteCredential(cred.id)" class="p-2 text-on-surface-variant hover:text-error hover:bg-red-50 rounded-lg transition-all">
                        <span class="material-symbols-outlined">delete</span>
                    </button>
                </div>
            </template>

            <div class="pt-3">
                <button @click="registerBiometric()" class="inline-flex items-center gap-1.5 px-6 py-2.5 bg-primary text-on-primary rounded-lg font-label-md hover:bg-primary/90 transition-all">
                    <span class="material-symbols-outlined text-lg">add</span>
                    Tambah Biometric
                </button>
            </div>
        </div>
    </template>

    <div x-show="error" x-cloak x-transition class="mt-4 p-3 bg-red-50 border border-red-200 text-error text-body-sm rounded-lg" x-text="error"></div>
</div>

<script>
function biometricManager() {
    return {
        credentials: [],
        loading: true,
        error: null,
        csrfToken: document.querySelector('meta[name="csrf-token"]')?.content,

        async init() {
            await this.loadCredentials();
        },

        async loadCredentials() {
            this.loading = true;
            this.error = null;
            try {
                const res = await fetch('/webauthn/credentials', {
                    headers: { 'X-CSRF-TOKEN': this.csrfToken }
                });
                if (!res.ok) throw new Error('Gagal memuat data biometric');
                this.credentials = await res.json();
            } catch (e) {
                this.error = e.message;
            } finally {
                this.loading = false;
            }
        },

        async registerBiometric() {
            this.error = null;
            try {
                const email = {{ Js::from(auth()->user()->email) }};
                if (!window.WebAuthn) {
                    throw new Error('Fitur biometric tidak didukung di browser ini');
                }
                await window.WebAuthn.registerBiometric(email);
                alert('Biometric berhasil didaftarkan!');
                await this.loadCredentials();
            } catch (e) {
                this.error = e.message;
            }
        },

        async deleteCredential(id) {
            if (!confirm('Hapus perangkat biometric ini?')) return;
            this.error = null;
            try {
                const res = await fetch('/webauthn/credentials/' + id, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': this.csrfToken,
                    }
                });
                if (!res.ok) throw new Error('Gagal menghapus biometric');
                this.credentials = this.credentials.filter(c => c.id !== id);
            } catch (e) {
                this.error = e.message;
            }
        }
    };
}
</script>
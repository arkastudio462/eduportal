<x-layouts.admin title="Jadwal Piket | EduPortal">
    <x-slot:styles>
    <style>
        [x-cloak] { display: none !important; }
    </style>
    </x-slot:styles>
<div x-data="{ openModal: false, form: { guru_id: '', hari: 'Senin' } }">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h2 class="font-headline-lg text-headline-lg text-primary">Jadwal Piket Guru</h2>
            <p class="text-on-surface-variant font-body-md">Kelola jadwal piket harian guru</p>
        </div>
        <button @click="openModal = true; form = { guru_id: '', hari: 'Senin' }" class="bg-primary text-on-primary px-6 py-2.5 rounded-lg flex items-center gap-2 font-label-md hover:bg-primary-container transition-all active:scale-95">
            <span class="material-symbols-outlined">add</span>
            Tambah Jadwal
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
        <div class="bg-white rounded-xl border border-outline-variant card-shadow p-6 flex items-center gap-4">
            <div class="w-12 h-12 bg-secondary-fixed rounded-xl flex items-center justify-center">
                <span class="material-symbols-outlined text-secondary">group</span>
            </div>
            <div>
                <p class="text-headline-sm font-headline-sm text-primary">{{ $jadwalPiket->flatten()->unique('guru_id')->count() }}</p>
                <p class="text-on-surface-variant font-body-md">Guru Piket</p>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-outline-variant card-shadow p-6 flex items-center gap-4">
            <div class="w-12 h-12 bg-tertiary-fixed rounded-xl flex items-center justify-center">
                <span class="material-symbols-outlined text-tertiary">calendar_month</span>
            </div>
            <div>
                <p class="text-headline-sm font-headline-sm text-primary">{{ collect($hariList)->filter(fn($h) => isset($jadwalPiket[$h]) && $jadwalPiket[$h]->isNotEmpty())->count() }}</p>
                <p class="text-on-surface-variant font-body-md">Hari Aktif</p>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-outline-variant card-shadow p-6 flex items-center gap-4">
            <div class="w-12 h-12 bg-primary-fixed rounded-xl flex items-center justify-center">
                <span class="material-symbols-outlined text-primary">assignment</span>
            </div>
            <div>
                <p class="text-headline-sm font-headline-sm text-primary">{{ $jadwalPiket->flatten()->count() }}</p>
                <p class="text-on-surface-variant font-body-md">Total Jadwal</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        @foreach($hariList as $hari)
        <div class="bg-white rounded-xl border border-outline-variant card-shadow overflow-hidden">
            <div class="px-6 py-4 bg-surface-container-low border-b border-outline-variant flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined text-secondary">today</span>
                    <h3 class="font-headline-sm text-headline-sm text-primary">{{ $hari }}</h3>
                </div>
                @php $count = isset($jadwalPiket[$hari]) ? $jadwalPiket[$hari]->count() : 0 @endphp
                <span class="px-3 py-1 bg-secondary-fixed text-secondary rounded-full text-xs font-bold">{{ $count }} guru</span>
            </div>
            <div class="divide-y divide-outline-variant">
                @forelse(($jadwalPiket[$hari] ?? []) as $piket)
                <div class="px-6 py-4 flex items-center justify-between hover:bg-surface-container transition-colors">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 bg-primary-fixed text-primary rounded-full flex items-center justify-center font-bold text-sm">
                            {{ substr($piket->guru->user->name, 0, 1) }}
                        </div>
                        <div>
                            <p class="font-body-md font-semibold">{{ $piket->guru->user->name }}</p>
                            <p class="text-xs text-on-surface-variant">{{ $piket->guru->mata_pelajaran ?? '-' }}</p>
                        </div>
                    </div>
                    <button onclick="Swal.fire({title:'Konfirmasi Hapus',text:'Hapus {{ addslashes($piket->guru->user->name) }} dari jadwal piket {{ $hari }}?',icon:'warning',showCancelButton:true,confirmButtonText:'Ya, hapus!',cancelButtonText:'Batal'}).then(r=>r.isConfirmed&&document.getElementById('delete-piket-{{ $piket->id }}').submit())" class="p-2 hover:bg-error/10 rounded-lg transition-colors">
                        <span class="material-symbols-outlined text-error">delete</span>
                    </button>
                    <form id="delete-piket-{{ $piket->id }}" method="POST" action="{{ route('admin.jadwal-piket.destroy', $piket->id) }}" class="hidden">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
                @empty
                <div class="px-6 py-8 text-center text-on-surface-variant">
                    <span class="material-symbols-outlined text-outline text-3xl block mb-2">block</span>
                    <p class="font-body-md">Belum ada jadwal</p>
                </div>
                @endforelse
            </div>
        </div>
        @endforeach
    </div>

    <div x-show="openModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" @click.away="openModal = false">
        <div class="fixed inset-0 bg-black/40" @click="openModal = false"></div>
        <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-lg p-8 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between mb-6">
                <h3 class="font-headline-sm text-headline-sm">Tambah Jadwal Piket</h3>
                <button @click="openModal = false" class="p-2 hover:bg-surface-container rounded-lg"><span class="material-symbols-outlined">close</span></button>
            </div>
            <form action="{{ route('admin.jadwal-piket.store') }}" method="POST" class="space-y-4">
                @csrf
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">Guru</label>
                    <select x-model="form.guru_id" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary" name="guru_id" required>
                        <option value="">Pilih Guru</option>
                        @foreach($semuaGuru as $guru)
                        <option value="{{ $guru->id }}">{{ $guru->user->name }} ({{ $guru->mata_pelajaran }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">Hari</label>
                    <select x-model="form.hari" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary" name="hari" required>
                        @foreach($hariList as $hari)
                        <option value="{{ $hari }}">{{ $hari }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex gap-3 pt-2">
                    <button type="button" @click="openModal = false" class="flex-1 py-3 border border-outline-variant rounded-xl font-label-md hover:bg-surface-container transition-all">Batal</button>
                    <button type="submit" class="flex-1 py-3 bg-primary text-on-primary rounded-xl font-label-md hover:bg-primary-container transition-all">Tambah</button>
                </div>
            </form>
        </div>
    </div>
</div>
</x-layouts.admin>

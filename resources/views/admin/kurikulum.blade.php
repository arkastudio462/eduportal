<x-layouts.admin title="Kurikulum | EduPortal">
    <x-slot:styles>
    <style>[x-cloak] { display: none !important; }</style>
    </x-slot:styles>

<div x-data="{
    tab: 'ki-kd',
    openModal: false,
    editMode: false,
    editId: null,
    deleteModal: false,
    deleteUrl: '',
    detailProtaId: null,
    detailProtaData: null,
    loadingDetail: false,
    promesItems: [{ bulan: '', materi: '', minggu_ke: '' }],
    form: {
        ki_kd: { mapel_id: '', kode: '', tipe: 'KI', deskripsi: '', semester: '' },
        silabus: { mapel_id: '', judul: '', deskripsi: '', semester: '', file: null },
        prota: { mapel_id: '', tahun_ajaran: '', deskripsi: '', file: null }
    },
    resetForm() {
        this.form = {
            ki_kd: { mapel_id: '', kode: '', tipe: 'KI', deskripsi: '', semester: '' },
            silabus: { mapel_id: '', judul: '', deskripsi: '', semester: '', file: null },
            prota: { mapel_id: '', tahun_ajaran: '', deskripsi: '', file: null }
        };
        this.promesItems = [{ bulan: '', materi: '', minggu_ke: '' }];
        this.editMode = false;
        this.editId = null;
    },
    openCreateModal() {
        this.resetForm();
        this.openModal = true;
    },
    openEditKiKd(id, mapel_id, kode, tipe, deskripsi, semester) {
        this.resetForm();
        this.editMode = true;
        this.editId = id;
        this.form.ki_kd = { mapel_id, kode, tipe, deskripsi, semester };
        this.openModal = true;
    },
    openEditSilabus(id, mapel_id, judul, deskripsi, semester) {
        this.resetForm();
        this.editMode = true;
        this.editId = id;
        this.form.silabus = { mapel_id, judul, deskripsi, semester, file: null };
        this.openModal = true;
    },
    getSubmitAction() {
        let base = '';
        if (this.tab === 'ki-kd') {
            base = this.editMode
                ? '{{ route('admin.kurikulum.update-ki-kd', '__ID__') }}'.replace('__ID__', this.editId)
                : '{{ route('admin.kurikulum.store-ki-kd') }}';
        } else if (this.tab === 'silabus') {
            base = this.editMode
                ? '{{ route('admin.kurikulum.update-silabus', '__ID__') }}'.replace('__ID__', this.editId)
                : '{{ route('admin.kurikulum.store-silabus') }}';
        } else if (this.tab === 'prota') {
            base = '{{ route('admin.kurikulum.store-prota') }}';
        }
        return base;
    },
    getMethod() {
        return this.editMode ? 'PUT' : 'POST';
    },
    confirmDelete(url) {
        this.deleteUrl = url;
        this.deleteModal = true;
    },
    addPromesRow() {
        this.promesItems.push({ bulan: '', materi: '', minggu_ke: '' });
    },
    removePromesRow(index) {
        if (this.promesItems.length > 1) {
            this.promesItems.splice(index, 1);
        }
    },
    loadDetailProta(id) {
        if (this.detailProtaId === id) {
            this.detailProtaId = null;
            return;
        }
        this.detailProtaId = id;
        this.loadingDetail = true;
        this.detailProtaData = null;
        fetch('{{ url('admin/kurikulum/prota') }}/' + id)
            .then(r => r.json())
            .then(data => {
                this.detailProtaData = data;
                this.loadingDetail = false;
            })
            .catch(() => {
                this.loadingDetail = false;
            });
    }
}">
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
        <h2 class="font-headline-lg text-headline-lg text-primary">Kurikulum</h2>
        <p class="text-on-surface-variant font-body-md">Kelola KI/KD, Silabus, dan Prota/Promes</p>
    </div>
    <button @click="openCreateModal()" class="px-4 py-2.5 bg-primary text-on-primary rounded-lg flex items-center gap-2 font-label-md hover:bg-primary-container transition-all active:scale-95">
        <span class="material-symbols-outlined">add</span>
        <span x-text="tab === 'ki-kd' ? 'Tambah KI/KD' : tab === 'silabus' ? 'Tambah Silabus' : 'Tambah Prota'"></span>
    </button>
</div>

{{-- Stats --}}
<div class="grid grid-cols-3 gap-gutter mb-6">
    <div class="bg-white rounded-xl border border-outline-variant card-shadow p-5 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-primary-fixed text-primary flex items-center justify-center"><span class="material-symbols-outlined" style="font-variation-settings:'FILL'1">menu_book</span></div>
        <div><p class="text-xs text-on-surface-variant">Total KI/KD</p><h3 class="font-headline-md text-headline-md text-primary">{{ $kiKd->count() }}</h3></div>
    </div>
    <div class="bg-white rounded-xl border border-outline-variant card-shadow p-5 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-secondary-fixed text-secondary flex items-center justify-center"><span class="material-symbols-outlined" style="font-variation-settings:'FILL'1">description</span></div>
        <div><p class="text-xs text-on-surface-variant">Total Silabus</p><h3 class="font-headline-md text-headline-md text-primary">{{ $silabus->count() }}</h3></div>
    </div>
    <div class="bg-white rounded-xl border border-outline-variant card-shadow p-5 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-tertiary-fixed text-tertiary flex items-center justify-center"><span class="material-symbols-outlined" style="font-variation-settings:'FILL'1">calendar_month</span></div>
        <div><p class="text-xs text-on-surface-variant">Total Prota</p><h3 class="font-headline-md text-headline-md text-primary">{{ $prota->count() }}</h3></div>
    </div>
</div>

{{-- Tab Navigation --}}
<div class="flex gap-1 bg-surface-container-low rounded-xl p-1 mb-6 w-fit">
    <button @click="tab = 'ki-kd'" :class="tab === 'ki-kd' ? 'bg-white text-primary shadow-sm' : 'text-on-surface-variant hover:text-primary'" class="px-5 py-2.5 rounded-lg font-label-md transition-all">
        <span class="flex items-center gap-2"><span class="material-symbols-outlined text-lg">menu_book</span> KI/KD</span>
    </button>
    <button @click="tab = 'silabus'" :class="tab === 'silabus' ? 'bg-white text-primary shadow-sm' : 'text-on-surface-variant hover:text-primary'" class="px-5 py-2.5 rounded-lg font-label-md transition-all">
        <span class="flex items-center gap-2"><span class="material-symbols-outlined text-lg">description</span> Silabus</span>
    </button>
    <button @click="tab = 'prota'" :class="tab === 'prota' ? 'bg-white text-primary shadow-sm' : 'text-on-surface-variant hover:text-primary'" class="px-5 py-2.5 rounded-lg font-label-md transition-all">
        <span class="flex items-center gap-2"><span class="material-symbols-outlined text-lg">calendar_month</span> Prota/Promes</span>
    </button>
</div>

{{-- Tab: KI/KD --}}
<div x-show="tab === 'ki-kd'">
    <div class="bg-white rounded-xl border border-outline-variant card-shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-surface-container-low">
                    <tr>
                        <th class="px-6 py-4 font-label-md text-on-surface-variant">Kode</th>
                        <th class="px-6 py-4 font-label-md text-on-surface-variant">Tipe</th>
                        <th class="px-6 py-4 font-label-md text-on-surface-variant">Deskripsi</th>
                        <th class="px-6 py-4 font-label-md text-on-surface-variant">Mapel</th>
                        <th class="px-6 py-4 font-label-md text-on-surface-variant">Semester</th>
                        <th class="px-6 py-4 font-label-md text-on-surface-variant">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant">
                    @forelse($kiKd as $item)
                    <tr class="hover:bg-surface-container transition-colors">
                        <td class="px-6 py-4 font-body-md font-semibold">{{ $item->kode }}</td>
                        <td class="px-6 py-4">
                            @if($item->tipe === 'KI')
                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-secondary-fixed text-secondary">KI</span>
                            @else
                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-tertiary-fixed text-tertiary">KD</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 max-w-xs truncate">{{ $item->deskripsi }}</td>
                        <td class="px-6 py-4">{{ $item->mapel->nama ?? '-' }}</td>
                        <td class="px-6 py-4 text-body-sm">{{ $item->semester }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <button @click="openEditKiKd('{{ $item->id }}', '{{ $item->mapel_id }}', '{{ $item->kode }}', '{{ $item->tipe }}', `{{ $item->deskripsi }}`, '{{ $item->semester }}')" class="p-2 hover:bg-surface-container-low rounded-lg">
                                    <span class="material-symbols-outlined text-outline">edit</span>
                                </button>
                                <button @click="confirmDelete('{{ route('admin.kurikulum.destroy-ki-kd', $item) }}')" class="p-2 hover:bg-error/10 rounded-lg">
                                    <span class="material-symbols-outlined text-error">delete</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td class="px-6 py-8 text-center text-on-surface-variant" colspan="6">Belum ada data KI/KD.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Tab: Silabus --}}
<div x-show="tab === 'silabus'">
    @if($silabus->count())
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-gutter">
        @foreach($silabus as $item)
        <div class="bg-white rounded-xl border border-outline-variant card-shadow p-5 flex flex-col gap-3">
            <div class="flex items-start justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-secondary-fixed text-secondary flex items-center justify-center flex-shrink-0">
                        <span class="material-symbols-outlined">description</span>
                    </div>
                    <div>
                        <h4 class="font-label-md text-label-md font-semibold text-primary">{{ $item->judul }}</h4>
                        <p class="text-xs text-on-surface-variant">{{ $item->mapel->nama ?? '-' }} &middot; {{ $item->semester }}</p>
                    </div>
                </div>
            </div>
            <p class="text-body-sm text-on-surface-variant line-clamp-2">{{ $item->deskripsi }}</p>
            <div class="flex items-center gap-2 mt-auto pt-2 border-t border-outline-variant">
                @if($item->file)
                <a href="{{ asset('storage/' . $item->file) }}" target="_blank" class="px-3 py-1.5 bg-secondary-fixed text-secondary rounded-lg text-xs font-bold flex items-center gap-1 hover:bg-secondary-fixed/80 transition-all">
                    <span class="material-symbols-outlined text-sm">download</span>
                    Download
                </a>
                @endif
                <div class="flex items-center gap-1 ml-auto">
                    <button @click="openEditSilabus('{{ $item->id }}', '{{ $item->mapel_id }}', '{{ $item->judul }}', `{{ $item->deskripsi }}`, '{{ $item->semester }}')" class="p-1.5 hover:bg-surface-container-low rounded-lg">
                        <span class="material-symbols-outlined text-sm text-outline">edit</span>
                    </button>
                    <button @click="confirmDelete('{{ route('admin.kurikulum.destroy-silabus', $item) }}')" class="p-1.5 hover:bg-error/10 rounded-lg">
                        <span class="material-symbols-outlined text-sm text-error">delete</span>
                    </button>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="bg-white rounded-xl border border-outline-variant card-shadow p-8 text-center text-on-surface-variant">
        <span class="material-symbols-outlined text-4xl mb-2 block">description</span>
        <p>Belum ada data Silabus.</p>
    </div>
    @endif
</div>

{{-- Tab: Prota/Promes --}}
<div x-show="tab === 'prota'">
    @if($prota->count())
    <div class="grid grid-cols-1 md:grid-cols-2 gap-gutter">
        @foreach($prota as $item)
        <div class="bg-white rounded-xl border border-outline-variant card-shadow overflow-hidden">
            <div class="p-5 flex items-start justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-tertiary-fixed text-tertiary flex items-center justify-center flex-shrink-0">
                        <span class="material-symbols-outlined">calendar_month</span>
                    </div>
                    <div>
                        <h4 class="font-label-md text-label-md font-semibold text-primary">{{ $item->mapel->nama ?? '-' }}</h4>
                        <p class="text-xs text-on-surface-variant">{{ $item->tahun_ajaran }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-1">
                    @if($item->file)
                    <a href="{{ asset('storage/' . $item->file) }}" target="_blank" class="p-1.5 hover:bg-surface-container-low rounded-lg">
                        <span class="material-symbols-outlined text-sm text-outline">download</span>
                    </a>
                    @endif
                    <button @click="confirmDelete('{{ route('admin.kurikulum.destroy-prota', $item) }}')" class="p-1.5 hover:bg-error/10 rounded-lg">
                        <span class="material-symbols-outlined text-sm text-error">delete</span>
                    </button>
                    <button @click="loadDetailProta({{ $item->id }})" class="p-1.5 hover:bg-surface-container-low rounded-lg">
                        <span class="material-symbols-outlined text-sm text-outline" x-text="detailProtaId === {{ $item->id }} ? 'expand_less' : 'expand_more'"></span>
                    </button>
                </div>
            </div>
            @if($item->deskripsi)
            <div class="px-5 pb-3">
                <p class="text-body-sm text-on-surface-variant">{{ $item->deskripsi }}</p>
            </div>
            @endif

            {{-- Promes Accordion --}}
            <div x-show="detailProtaId === {{ $item->id }}" x-cloak>
                <div class="border-t border-outline-variant bg-surface-container-low px-5 py-3">
                    <div x-show="loadingDetail" class="text-center py-4">
                        <span class="material-symbols-outlined animate-spin text-outline">sync</span>
                    </div>
                    <template x-if="detailProtaData && detailProtaData.id === {{ $item->id }}">
                        <div>
                            <template x-if="detailProtaData.promes && detailProtaData.promes.length">
                                <div class="space-y-2">
                                    <template x-for="(promes, idx) in detailProtaData.promes" :key="idx">
                                        <div class="flex items-center gap-3 bg-white rounded-lg px-4 py-2.5 border border-outline-variant">
                                            <span class="text-xs font-bold text-secondary bg-secondary-fixed px-2 py-0.5 rounded" x-text="promes.bulan"></span>
                                            <span class="text-body-sm flex-1" x-text="promes.materi"></span>
                                            <span class="text-xs text-on-surface-variant">Minggu ke-<span x-text="promes.minggu_ke"></span></span>
                                        </div>
                                    </template>
                                </div>
                            </template>
                            <template x-if="!detailProtaData.promes || !detailProtaData.promes.length">
                                <p class="text-center text-body-sm text-on-surface-variant py-2">Belum ada program semester.</p>
                            </template>
                        </div>
                    </template>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="bg-white rounded-xl border border-outline-variant card-shadow p-8 text-center text-on-surface-variant">
        <span class="material-symbols-outlined text-4xl mb-2 block">calendar_month</span>
        <p>Belum ada data Prota.</p>
    </div>
    @endif
</div>

{{-- CRUD Modal --}}
<div x-show="openModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" @click.away="openModal = false">
    <div class="fixed inset-0 bg-black/40" @click="openModal = false"></div>
    <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-lg p-8 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between mb-6">
            <h3 class="font-headline-sm text-headline-sm">
                <template x-if="tab === 'ki-kd'">
                    <span x-text="editMode ? 'Edit KI/KD' : 'Tambah KI/KD'"></span>
                </template>
                <template x-if="tab === 'silabus'">
                    <span x-text="editMode ? 'Edit Silabus' : 'Tambah Silabus'"></span>
                </template>
                <template x-if="tab === 'prota'">
                    <span>Tambah Prota</span>
                </template>
            </h3>
            <button @click="openModal = false" class="p-2 hover:bg-surface-container rounded-lg"><span class="material-symbols-outlined">close</span></button>
        </div>

        {{-- KI/KD Form --}}
        <form x-show="tab === 'ki-kd'" :action="getSubmitAction()" method="POST" class="space-y-4">
            @csrf
            <template x-if="editMode"><input type="hidden" name="_method" value="PUT"></template>
            <div class="space-y-2">
                <label class="font-label-md text-label-md text-on-surface-variant">Mata Pelajaran</label>
                <select x-model="form.ki_kd.mapel_id" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary" name="mapel_id" required>
                    <option value="">-- Pilih Mapel --</option>
                    @foreach($daftarMapel as $mapel)
                    <option value="{{ $mapel->id }}">{{ $mapel->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">Kode</label>
                    <input x-model="form.ki_kd.kode" type="text" name="kode" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary" placeholder="3.1" required>
                </div>
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">Tipe</label>
                    <select x-model="form.ki_kd.tipe" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary" name="tipe" required>
                        <option value="KI">KI (Kompetensi Inti)</option>
                        <option value="KD">KD (Kompetensi Dasar)</option>
                    </select>
                </div>
            </div>
            <div class="space-y-2">
                <label class="font-label-md text-label-md text-on-surface-variant">Deskripsi</label>
                <textarea x-model="form.ki_kd.deskripsi" name="deskripsi" rows="3" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary" required></textarea>
            </div>
            <div class="space-y-2">
                <label class="font-label-md text-label-md text-on-surface-variant">Semester</label>
                <input x-model="form.ki_kd.semester" type="text" name="semester" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary" placeholder="1 (Ganjil)" required>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="button" @click="openModal = false" class="flex-1 py-3 border border-outline-variant rounded-xl font-label-md hover:bg-surface-container">Batal</button>
                <button type="submit" class="flex-1 py-3 bg-primary text-on-primary rounded-xl font-label-md hover:bg-primary-container" x-text="editMode ? 'Simpan' : 'Tambah'"></button>
            </div>
        </form>

        {{-- Silabus Form --}}
        <form x-show="tab === 'silabus'" :action="getSubmitAction()" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <template x-if="editMode"><input type="hidden" name="_method" value="PUT"></template>
            <div class="space-y-2">
                <label class="font-label-md text-label-md text-on-surface-variant">Mata Pelajaran</label>
                <select x-model="form.silabus.mapel_id" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary" name="mapel_id" required>
                    <option value="">-- Pilih Mapel --</option>
                    @foreach($daftarMapel as $mapel)
                    <option value="{{ $mapel->id }}">{{ $mapel->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="space-y-2">
                <label class="font-label-md text-label-md text-on-surface-variant">Judul</label>
                <input x-model="form.silabus.judul" type="text" name="judul" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary" placeholder="Silabus ..." required>
            </div>
            <div class="space-y-2">
                <label class="font-label-md text-label-md text-on-surface-variant">Deskripsi</label>
                <textarea x-model="form.silabus.deskripsi" name="deskripsi" rows="3" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary"></textarea>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">Semester</label>
                    <input x-model="form.silabus.semester" type="text" name="semester" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary" placeholder="Ganjil" required>
                </div>
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">File (PDF/DOC)</label>
                    <input type="file" name="file" accept=".pdf,.doc,.docx" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-3 py-2.5 focus:outline-none focus:border-primary file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:bg-primary file:text-on-primary file:text-xs file:font-bold hover:file:bg-primary-container cursor-pointer">
                </div>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="button" @click="openModal = false" class="flex-1 py-3 border border-outline-variant rounded-xl font-label-md hover:bg-surface-container">Batal</button>
                <button type="submit" class="flex-1 py-3 bg-primary text-on-primary rounded-xl font-label-md hover:bg-primary-container" x-text="editMode ? 'Simpan' : 'Tambah'"></button>
            </div>
        </form>

        {{-- Prota Form --}}
        <form x-show="tab === 'prota'" action="{{ route('admin.kurikulum.store-prota') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div class="space-y-2">
                <label class="font-label-md text-label-md text-on-surface-variant">Mata Pelajaran</label>
                <select x-model="form.prota.mapel_id" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary" name="mapel_id" required>
                    <option value="">-- Pilih Mapel --</option>
                    @foreach($daftarMapel as $mapel)
                    <option value="{{ $mapel->id }}">{{ $mapel->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="space-y-2">
                <label class="font-label-md text-label-md text-on-surface-variant">Tahun Ajaran</label>
                <input x-model="form.prota.tahun_ajaran" type="text" name="tahun_ajaran" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary" placeholder="2024/2025" required>
            </div>
            <div class="space-y-2">
                <label class="font-label-md text-label-md text-on-surface-variant">Deskripsi</label>
                <textarea x-model="form.prota.deskripsi" name="deskripsi" rows="2" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary"></textarea>
            </div>
            <div class="space-y-2">
                <label class="font-label-md text-label-md text-on-surface-variant">File (Opsional)</label>
                <input type="file" name="file" accept=".pdf,.doc,.docx" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-3 py-2.5 focus:outline-none focus:border-primary file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:bg-primary file:text-on-primary file:text-xs file:font-bold hover:file:bg-primary-container cursor-pointer">
            </div>

            {{-- Promes Rows --}}
            <div>
                <div class="flex items-center justify-between mb-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">Program Semester (Promes)</label>
                    <button type="button" @click="addPromesRow()" class="text-xs text-secondary flex items-center gap-1 font-bold hover:underline">
                        <span class="material-symbols-outlined text-sm">add</span> Tambah Baris
                    </button>
                </div>
                <template x-for="(item, index) in promesItems" :key="index">
                    <div class="flex items-start gap-2 mb-2">
                        <div class="flex-1 grid grid-cols-3 gap-2">
                            <select :name="'bulan[' + index + ']'" x-model="item.bulan" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-primary" required>
                                <option value="">Bulan</option>
                                <option value="Januari">Januari</option>
                                <option value="Februari">Februari</option>
                                <option value="Maret">Maret</option>
                                <option value="April">April</option>
                                <option value="Mei">Mei</option>
                                <option value="Juni">Juni</option>
                                <option value="Juli">Juli</option>
                                <option value="Agustus">Agustus</option>
                                <option value="September">September</option>
                                <option value="Oktober">Oktober</option>
                                <option value="November">November</option>
                                <option value="Desember">Desember</option>
                            </select>
                            <input type="text" :name="'materi[' + index + ']'" x-model="item.materi" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-primary" placeholder="Materi" required>
                            <input type="number" :name="'minggu_ke[' + index + ']'" x-model="item.minggu_ke" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-primary" placeholder="Minggu ke" min="1" max="5" required>
                        </div>
                        <button type="button" @click="removePromesRow(index)" x-show="promesItems.length > 1" class="p-2 hover:bg-error/10 rounded-lg mt-1">
                            <span class="material-symbols-outlined text-sm text-error">remove_circle</span>
                        </button>
                    </div>
                </template>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="button" @click="openModal = false" class="flex-1 py-3 border border-outline-variant rounded-xl font-label-md hover:bg-surface-container">Batal</button>
                <button type="submit" class="flex-1 py-3 bg-primary text-on-primary rounded-xl font-label-md hover:bg-primary-container">Tambah</button>
            </div>
        </form>
    </div>
</div>

{{-- Delete Modal --}}
<div x-show="deleteModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" @click.away="deleteModal = false">
    <div class="fixed inset-0 bg-black/40" @click="deleteModal = false"></div>
    <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-md p-6">
        <div class="flex items-center gap-4 mb-4">
            <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                <span class="material-symbols-outlined text-red-600 text-2xl">delete_forever</span>
            </div>
            <div>
                <h3 class="font-headline-sm text-headline-sm">Konfirmasi Hapus</h3>
                <p class="text-sm text-on-surface-variant mt-1">Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.</p>
            </div>
        </div>
        <div class="flex justify-end gap-3 mt-6">
            <button @click="deleteModal = false" type="button" class="px-4 py-2 border border-outline-variant rounded-lg font-label-md hover:bg-surface-container transition-all">Batal</button>
            <form method="POST" :action="deleteUrl" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg font-label-md hover:bg-red-700 transition-all">Hapus</button>
            </form>
        </div>
    </div>
</div>
</div>
</x-layouts.admin>

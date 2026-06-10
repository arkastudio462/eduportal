<x-layouts.admin title="Bank Soal | EduPortal">
<div x-data="{ openImport: false }">
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
        <h2 class="font-headline-lg text-headline-lg text-primary">Bank Soal</h2>
        <p class="text-on-surface-variant font-body-md">Kelola & Arsip Soal Ujian</p>
    </div>
    <div class="flex gap-3">
        <button @click="openImport = true" class="px-4 py-2.5 border border-outline-variant rounded-lg flex items-center gap-2 font-label-md text-outline hover:bg-surface-container transition-all">
            <span class="material-symbols-outlined">upload_file</span>
            Import Soal
        </button>
        <a href="{{ route('admin.bank-soal') }}?tambah=1"
           class="px-4 py-2.5 bg-primary text-on-primary rounded-lg flex items-center gap-2 font-label-md hover:bg-primary-container transition-all active:scale-95">
            <span class="material-symbols-outlined">add</span>
            Tambah Soal
        </a>
    </div>
</div>

<!-- Stats -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-gutter mb-6">
    @php $soalStats = [['Total Soal', $totalSoal, 'library_books', 'bg-primary-fixed text-primary'], ['PG', $totalPg, 'radio_button_checked', 'bg-secondary-fixed text-secondary'], ['Essay', $totalEssay, 'text_fields', 'bg-tertiary-fixed text-on-tertiary-container'], ['Ganda Kompleks', $totalGandaKompleks, 'checklist', 'bg-green-100 text-green-700']]; @endphp
    @foreach($soalStats as $stat)
    <div class="bg-white p-5 rounded-xl border border-outline-variant card-shadow hover:translate-y-[-4px] transition-transform">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl {{ $stat[3] }} flex items-center justify-center">
                <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">{{ $stat[2] }}</span>
            </div>
            <div>
                <p class="text-on-surface-variant font-label-md">{{ $stat[0] }}</p>
                <h3 class="font-headline-md text-headline-md text-primary">{{ $stat[1] }}</h3>
            </div>
        </div>
    </div>
    @endforeach
</div>

<!-- Trash Filter Tabs -->
<div class="flex items-center gap-2 mb-4">
    <a href="{{ route('admin.bank-soal') }}" class="px-4 py-1.5 rounded-full text-sm font-label-md border transition-all {{ !request('trashed') ? 'bg-primary text-on-primary border-primary' : 'border-outline-variant hover:bg-surface-container' }}">Aktif</a>
    <a href="{{ route('admin.bank-soal', ['trashed' => 1]) }}" class="px-4 py-1.5 rounded-full text-sm font-label-md border transition-all {{ request('trashed') ? 'bg-primary text-on-primary border-primary' : 'border-outline-variant hover:bg-surface-container' }}">Sampah</a>
</div>

<!-- Import Modal -->
<div x-show="openImport" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="fixed inset-0 bg-black/40" @click="openImport = false"></div>
    <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-lg z-10 p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="font-headline-md text-headline-md text-primary">Import Soal</h3>
            <button type="button" @click="openImport = false" class="p-2 hover:bg-surface-container rounded-full"><span class="material-symbols-outlined">close</span></button>
        </div>
        <form method="POST" action="{{ route('admin.bank-soal.import') }}" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-on-surface-variant mb-1">Mata Pelajaran</label>
                <select name="mapel_id" class="w-full px-3 py-2 border border-outline-variant rounded-lg text-sm focus:ring-2 focus:ring-primary focus:border-primary outline-none bg-white" required>
                    <option value="">Pilih Mapel</option>
                    @foreach($daftarMapel as $mapel)
                    <option value="{{ $mapel->id }}">{{ $mapel->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-on-surface-variant mb-1">File JSON</label>
                <input type="file" name="file" accept=".json,.txt" required class="w-full px-3 py-2 border border-outline-variant rounded-lg text-sm focus:ring-2 focus:ring-primary focus:border-primary outline-none file:mr-4 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-sm file:bg-primary file:text-on-primary hover:file:bg-primary-container">
                <p class="text-xs text-on-surface-variant mt-1">Format: JSON array dengan field mapel_id, tipe (PG/Essay/Ganda Kompleks), kesulitan (Mudah/Sedang/Sulit), konten, opsi (array untuk PG/Ganda Kompleks), jawaban</p>
            </div>
            <div class="flex justify-end gap-3">
                <button type="button" @click="openImport = false" class="px-6 py-2.5 border border-outline-variant rounded-lg font-label-md text-outline hover:bg-surface-container transition-all">Batal</button>
                <button type="submit" class="px-6 py-2.5 bg-primary text-on-primary rounded-lg font-label-md hover:bg-primary-container transition-all active:scale-95">Import</button>
            </div>
        </form>
    </div>
</div>

</div>

<!-- Subject Tabs & Filter -->
<div class="bg-white rounded-xl border border-outline-variant card-shadow overflow-hidden" x-data="soalApp()">
    <!-- Bulk Action Bar -->
    <div x-show="selectedIds.length > 0" x-cloak class="flex items-center gap-3 px-4 py-3 bg-surface-container border-b border-outline-variant">
        <span class="text-sm text-on-surface-variant" x-text="selectedIds.length + ' dipilih'"></span>
        <div class="flex gap-2 ml-auto">
            <form method="POST" :action="'{{ route('admin.bank-soal.bulk-destroy') }}'" @submit.prevent="Swal.fire({title:'Konfirmasi',text:'Hapus soal yang dipilih?',icon:'warning',showCancelButton:true,confirmButtonText:'Ya, hapus!',cancelButtonText:'Batal'}).then(r=>r.isConfirmed&&$el.submit())">
                @csrf
                <input type="hidden" name="ids" :value="JSON.stringify(selectedIds)">
                <button type="submit" class="px-4 py-1.5 bg-error text-white rounded-lg text-sm font-label-md hover:bg-error/80 transition-all">Hapus</button>
            </form>
            @if(request('trashed'))
            <form method="POST" :action="'{{ route('admin.bank-soal.bulk-restore') }}'">
                @csrf
                <input type="hidden" name="ids" :value="JSON.stringify(selectedIds)">
                <button type="submit" class="px-4 py-1.5 bg-primary text-on-primary rounded-lg text-sm font-label-md hover:bg-primary-container transition-all">Pulihkan</button>
            </form>
            @endif
            <button @click="selectedIds = []; selectAll = false" class="px-4 py-1.5 border border-outline-variant rounded-lg text-sm font-label-md text-outline hover:bg-surface-container transition-all">Batal</button>
        </div>
    </div>
    <div class="p-4 md:p-6 border-b border-outline-variant">
        <form method="GET" action="{{ route('admin.bank-soal') }}" class="flex flex-wrap items-center gap-2 mb-4">
            @foreach($daftarMapel as $mapel)
            <button name="mapel" value="{{ $mapel->id }}" class="px-4 py-1.5 rounded-full text-sm font-label-md border border-outline-variant hover:bg-primary-fixed hover:border-primary hover:text-primary transition-all {{ request('mapel') == $mapel->id ? 'bg-primary text-on-primary border-primary' : '' }}">
                {{ $mapel->nama }}
            </button>
            @endforeach
            <button name="mapel" value="" class="px-4 py-1.5 rounded-full text-sm font-label-md border border-outline-variant hover:bg-primary-fixed hover:border-primary hover:text-primary transition-all {{ !request('mapel') ? 'bg-primary text-on-primary border-primary' : '' }}">
                Semua Mapel
            </button>
        </form>
        <form method="GET" action="{{ route('admin.bank-soal') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-4">
            <div class="relative">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline text-sm">search</span>
                <input name="search" value="{{ request('search') }}" class="w-full pl-10 pr-3 py-2 border border-outline-variant rounded-lg text-sm focus:ring-2 focus:ring-primary focus:border-primary outline-none" placeholder="Cari soal..." type="text">
            </div>
            <select name="tipe" class="px-3 py-2 border border-outline-variant rounded-lg text-sm bg-white focus:ring-2 focus:ring-primary focus:border-primary outline-none">
                <option value="">Tipe Soal</option>
                <option value="PG" {{ request('tipe') == 'PG' ? 'selected' : '' }}>Pilihan Ganda</option>
                <option value="Essay" {{ request('tipe') == 'Essay' ? 'selected' : '' }}>Essay</option>
                <option value="Ganda Kompleks" {{ request('tipe') == 'Ganda Kompleks' ? 'selected' : '' }}>Ganda Kompleks</option>
            </select>
            <select name="kesulitan" class="px-3 py-2 border border-outline-variant rounded-lg text-sm bg-white focus:ring-2 focus:ring-primary focus:border-primary outline-none">
                <option value="">Tingkat Kesulitan</option>
                <option value="Mudah" {{ request('kesulitan') == 'Mudah' ? 'selected' : '' }}>Mudah</option>
                <option value="Sedang" {{ request('kesulitan') == 'Sedang' ? 'selected' : '' }}>Sedang</option>
                <option value="Sulit" {{ request('kesulitan') == 'Sulit' ? 'selected' : '' }}>Sulit</option>
            </select>
            <button type="submit" class="mt-3 sm:mt-0 px-4 py-2 bg-secondary-container text-on-secondary-container rounded-lg font-label-md text-sm hover:bg-secondary-container/70 transition-all">Terapkan Filter</button>
        </form>
    </div>

    <!-- Soal List -->
    <div class="divide-y divide-outline-variant">
        @forelse($semuaSoal as $soal)
        <div class="p-4 md:p-6 hover:bg-surface-container transition-all">
            <div class="flex items-start gap-3">
                <div class="mt-1"><input type="checkbox" :value="{{ $soal->id }}" x-model="selectedIds" class="soal-checkbox rounded border-outline-variant"></div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="px-2 py-0.5 rounded text-xs font-bold bg-primary-fixed text-primary">{{ $soal->mapel->nama ?? '-' }}</span>
                        <span class="px-2 py-0.5 rounded text-xs font-bold bg-secondary-fixed text-secondary">{{ $soal->tipe }}</span>
                        <span class="px-2 py-0.5 rounded text-xs font-bold {{ $soal->kesulitan == 'Sulit' ? 'text-red-600 bg-red-100' : ($soal->kesulitan == 'Sedang' ? 'text-amber-600 bg-amber-100' : 'text-green-600 bg-green-100') }}">{{ $soal->kesulitan }}</span>
                    </div>
                    <p class="font-body-md font-semibold truncate">{{ Str::limit($soal->konten, 100) }}</p>
                    @if($soal->gambar)
                    <div class="mt-2">
                        <img src="{{ $soal->gambar }}" alt="Gambar soal" class="w-20 h-14 object-cover rounded-lg border border-outline-variant">
                    </div>
                    @endif
                </div>
                <div class="flex items-center gap-1 shrink-0">
                    <button @click="editSoal({{ $soal->id }})" class="p-1.5 hover:bg-surface-container rounded" title="Edit"><span class="material-symbols-outlined text-outline text-sm">edit</span></button>
                    <button @click="previewSoal({{ $soal->id }})" class="p-1.5 hover:bg-surface-container rounded" title="Preview"><span class="material-symbols-outlined text-outline text-sm">visibility</span></button>
                    <button @click="confirmDelete({{ $soal->id }})" class="p-1.5 hover:bg-error-container rounded" title="Hapus"><span class="material-symbols-outlined text-outline text-sm">delete</span></button>
                    <form method="POST" action="{{ route('admin.bank-soal.destroy', $soal->id) }}" id="delete-form-{{ $soal->id }}" class="hidden">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="p-6 text-on-surface-variant text-center">Tidak ada soal.</div>
        @endforelse
    </div>
    <div class="px-6 py-4 border-t border-outline-variant">
        {{ $semuaSoal->links() }}
    </div>

    <!-- Create/Edit Modal -->
    <div x-show="openModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" x-effect="if (openModal) { $nextTick(() => { document.body.style.overflow = 'hidden'; }) } else { document.body.style.overflow = '' }">
        <div class="fixed inset-0 bg-black/40" @click="openModal = false"></div>
        <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto z-10 p-6">
            <form method="POST" enctype="multipart/form-data" :action="editId ? '{{ route('admin.bank-soal.update', '__ID__') }}'.replace('__ID__', editId) : '{{ route('admin.bank-soal.store') }}'">
                @csrf
                <input type="hidden" name="_method" :value="editId ? 'PUT' : 'POST'">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="font-headline-md text-headline-md text-primary" x-text="editId ? 'Edit Soal' : 'Tambah Soal'"></h3>
                    <button type="button" @click="openModal = false" class="p-2 hover:bg-surface-container rounded-full"><span class="material-symbols-outlined">close</span></button>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-on-surface-variant mb-1">Mata Pelajaran</label>
                        <select name="mapel_id" x-model="form.mapel_id" class="w-full px-3 py-2 border border-outline-variant rounded-lg text-sm bg-white focus:ring-2 focus:ring-primary focus:border-primary outline-none" required>
                            <option value="">Pilih Mapel</option>
                            @foreach($daftarMapel as $mapel)
                            <option value="{{ $mapel->id }}">{{ $mapel->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-on-surface-variant mb-1">Tipe Soal</label>
                        <select name="tipe" x-model="form.tipe" @change="form.jawaban = ''; generateOpsi()" class="w-full px-3 py-2 border border-outline-variant rounded-lg text-sm bg-white focus:ring-2 focus:ring-primary focus:border-primary outline-none" required>
                            <option value="">Pilih Tipe</option>
                            <option value="PG">Pilihan Ganda</option>
                            <option value="Essay">Essay</option>
                            <option value="Ganda Kompleks">Ganda Kompleks</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-on-surface-variant mb-1">Tingkat Kesulitan</label>
                        <select name="kesulitan" x-model="form.kesulitan" class="w-full px-3 py-2 border border-outline-variant rounded-lg text-sm bg-white focus:ring-2 focus:ring-primary focus:border-primary outline-none" required>
                            <option value="">Pilih Kesulitan</option>
                            <option value="Mudah">Mudah</option>
                            <option value="Sedang">Sedang</option>
                            <option value="Sulit">Sulit</option>
                        </select>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-on-surface-variant mb-1">Konten Soal</label>
                    <textarea name="konten" x-model="form.konten" rows="3" class="w-full px-3 py-2 border border-outline-variant rounded-lg text-sm focus:ring-2 focus:ring-primary focus:border-primary outline-none" required></textarea>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-on-surface-variant mb-1">Gambar (opsional)</label>
                    <input type="file" name="gambar" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp" @change="previewGambar($event)" class="w-full px-3 py-2 border border-outline-variant rounded-lg text-sm focus:ring-2 focus:ring-primary focus:border-primary outline-none file:mr-4 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-sm file:bg-primary file:text-on-primary hover:file:bg-primary-container">
                    <template x-if="form.gambarPreview">
                        <div class="mt-2 relative inline-block">
                            <img :src="form.gambarPreview" class="w-40 h-28 object-cover rounded-lg border border-outline-variant">
                            <button type="button" @click="form.gambarPreview = null; $el.closest('.mb-4').querySelector('input[type=file]').value = ''" class="absolute -top-2 -right-2 w-5 h-5 bg-error text-white rounded-full flex items-center justify-center text-xs">&times;</button>
                        </div>
                    </template>
                    <template x-if="form.gambar && !form.gambarPreview && editId">
                        <div class="mt-2 relative inline-block">
                            <img :src="form.gambar" class="w-40 h-28 object-cover rounded-lg border border-outline-variant">
                            <span class="text-xs text-on-surface-variant mt-1 block">Klik Browse untuk mengganti</span>
                        </div>
                    </template>
                </div>

                <!-- Options for PG / Ganda Kompleks -->
                <template x-if="form.tipe === 'PG' || form.tipe === 'Ganda Kompleks'">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-on-surface-variant mb-2">Opsi Jawaban</label>
                        <template x-for="(opt, idx) in form.opsi" :key="idx">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="w-6 text-sm font-bold text-primary" x-text="String.fromCharCode(65 + idx)"></span>
                                <input type="text" x-model="opt.value" class="flex-1 px-3 py-2 border border-outline-variant rounded-lg text-sm focus:ring-2 focus:ring-primary focus:border-primary outline-none" placeholder="Teks opsi...">
                                <button type="button" @click="form.opsi.splice(idx, 1)" class="p-1 hover:bg-error-container rounded" x-show="form.opsi.length > 2"><span class="material-symbols-outlined text-sm text-error">remove</span></button>
                            </div>
                        </template>
                        <button type="button" @click="form.opsi.push({label: '', value: ''})" class="mt-1 text-sm text-primary font-bold flex items-center gap-1 hover:underline">
                            <span class="material-symbols-outlined text-sm">add</span> Tambah Opsi
                        </button>
                        <textarea name="opsi" x-model="opsiJson" class="hidden"></textarea>
                    </div>
                </template>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-on-surface-variant mb-1">Jawaban Benar</label>
                    <template x-if="form.tipe === 'PG'">
                        <select name="jawaban" x-model="form.jawaban" class="w-full px-3 py-2 border border-outline-variant rounded-lg text-sm bg-white focus:ring-2 focus:ring-primary focus:border-primary outline-none" required>
                            <option value="">Pilih Jawaban</option>
                            <template x-for="(opt, idx) in form.opsi" :key="idx">
                                <option :value="opt.value" x-text="String.fromCharCode(65 + idx) + '. ' + opt.value"></option>
                            </template>
                        </select>
                    </template>
                    <template x-if="form.tipe === 'Ganda Kompleks'">
                        <div class="space-y-1">
                            <template x-for="(opt, idx) in form.opsi" :key="idx">
                                <label class="flex items-center gap-2 text-sm">
                                    <input type="checkbox" :value="opt.value" :checked="form.jawaban.split(',').includes(opt.value)" @change="toggleGandaKompleks(opt.value)">
                                    <span x-text="String.fromCharCode(65 + idx) + '. ' + opt.value"></span>
                                </label>
                            </template>
                            <input type="hidden" name="jawaban" :value="form.jawaban">
                        </div>
                    </template>
                    <template x-if="form.tipe === 'Essay'">
                        <textarea name="jawaban" x-model="form.jawaban" rows="2" class="w-full px-3 py-2 border border-outline-variant rounded-lg text-sm focus:ring-2 focus:ring-primary focus:border-primary outline-none" placeholder="Kunci jawaban essay..."></textarea>
                    </template>
                </div>

                <div class="flex justify-end gap-3">
                    <button type="button" @click="openModal = false" class="px-6 py-2.5 border border-outline-variant rounded-lg font-label-md text-outline hover:bg-surface-container transition-all">Batal</button>
                    <button type="submit" class="px-6 py-2.5 bg-primary text-on-primary rounded-lg font-label-md hover:bg-primary-container transition-all active:scale-95" x-text="editId ? 'Simpan Perubahan' : 'Simpan'"></button>
                </div>
            </form>
        </div>
    </div>

    <!-- Preview Modal -->
    <div x-show="openPreview" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-black/40" @click="openPreview = false"></div>
        <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto z-10 p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="font-headline-md text-headline-md text-primary">Preview Soal</h3>
                <button type="button" @click="openPreview = false" class="p-2 hover:bg-surface-container rounded-full"><span class="material-symbols-outlined">close</span></button>
            </div>
            <template x-if="previewData">
                <div>
                    <div class="flex items-center gap-2 mb-4">
                        <span class="px-2 py-0.5 rounded text-xs font-bold bg-primary-fixed text-primary" x-text="previewData.mapel_nama"></span>
                        <span class="px-2 py-0.5 rounded text-xs font-bold bg-secondary-fixed text-secondary" x-text="previewData.tipe"></span>
                        <span class="px-2 py-0.5 rounded text-xs font-bold" :class="previewData.kesulitan === 'Sulit' ? 'text-red-600 bg-red-100' : (previewData.kesulitan === 'Sedang' ? 'text-amber-600 bg-amber-100' : 'text-green-600 bg-green-100')" x-text="previewData.kesulitan"></span>
                    </div>
                    <p class="text-body-md font-medium mb-4" x-text="previewData.konten"></p>
                    <template x-if="previewData.gambar">
                        <div class="mb-4">
                            <img :src="previewData.gambar" alt="Gambar soal" class="max-w-full max-h-64 rounded-lg border border-outline-variant">
                        </div>
                    </template>
                    <template x-if="previewData.opsi && previewData.tipe !== 'Essay'">
                        <div class="space-y-2">
                            <template x-for="(opt, idx) in previewData.parsedOpsi" :key="idx">
                                <div class="flex items-center gap-3 p-3 rounded-lg border" :class="previewData.jawaban === opt.value || previewData.jawaban.split(',').includes(opt.value) ? 'border-success bg-green-50' : 'border-outline-variant'">
                                    <span class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold" :class="previewData.jawaban === opt.value || previewData.jawaban.split(',').includes(opt.value) ? 'bg-success text-white' : 'bg-surface-container text-outline'" x-text="String.fromCharCode(65 + idx)"></span>
                                    <span x-text="opt.value"></span>
                                    <span x-show="previewData.jawaban === opt.value || previewData.jawaban.split(',').includes(opt.value)" class="material-symbols-outlined text-success text-sm ml-auto">check</span>
                                </div>
                            </template>
                        </div>
                    </template>
                    <template x-if="previewData.tipe === 'Essay'">
                        <div class="p-3 rounded-lg border border-outline-variant">
                            <span class="text-xs text-on-surface-variant">Kunci Jawaban:</span>
                            <p class="text-sm mt-1" x-text="previewData.jawaban"></p>
                        </div>
                    </template>
                </div>
            </template>
        </div>
    </div>

</div>

<x-slot:scripts>
<script>
function soalApp() {
    return {
        openModal: {{ request('tambah') ? 'true' : 'false' }},
        openImport: false,
        openPreview: false,
        editId: null,
        previewData: null,
        form: {
            mapel_id: '',
            tipe: '',
            kesulitan: '',
            konten: '',
            gambar: '',
            gambarPreview: null,
            opsi: [{label: 'A', value: ''}, {label: 'B', value: ''}],
            jawaban: '',
        },
        get opsiJson() {
            return JSON.stringify(this.form.opsi);
        },
        previewGambar(event) {
            const file = event.target.files[0];
            if (file) {
                this.form.gambarPreview = URL.createObjectURL(file);
            }
        },
        generateOpsi() {
            if (this.form.tipe === 'PG' || this.form.tipe === 'Ganda Kompleks') {
                if (this.form.opsi.length === 0 || (this.form.opsi.length === 2 && !this.form.opsi[0].value && !this.form.opsi[1].value)) {
                    this.form.opsi = [{label: 'A', value: ''}, {label: 'B', value: ''}];
                }
            } else {
                this.form.opsi = [];
            }
        },
        toggleGandaKompleks(val) {
            let arr = this.form.jawaban ? this.form.jawaban.split(',') : [];
            if (arr.includes(val)) {
                arr = arr.filter(v => v !== val);
            } else {
                arr.push(val);
            }
            this.form.jawaban = arr.join(',');
        },
        resetForm() {
            this.editId = null;
            this.form = { mapel_id: '', tipe: '', kesulitan: '', konten: '', gambar: '', gambarPreview: null, opsi: [{label: 'A', value: ''}, {label: 'B', value: ''}], jawaban: '' };
        },
        editSoal(id) {
            fetch('{{ route('admin.bank-soal.show', '__ID__') }}'.replace('__ID__', id))
                .then(r => r.json())
                .then(data => {
                    this.editId = data.id;
                    this.form.mapel_id = data.mapel_id;
                    this.form.tipe = data.tipe;
                    this.form.kesulitan = data.kesulitan;
                    this.form.konten = data.konten;
                    this.form.gambar = data.gambar || '';
                    this.form.gambarPreview = null;
                    this.form.jawaban = data.jawaban;
                    if (data.opsi) {
                        try { this.form.opsi = JSON.parse(data.opsi); } catch(e) { this.form.opsi = []; }
                    } else {
                        this.form.opsi = [];
                    }
                    this.openModal = true;
                });
        },
        previewSoal(id) {
            fetch('{{ route('admin.bank-soal.show', '__ID__') }}'.replace('__ID__', id))
                .then(r => r.json())
                .then(data => {
                    let parsedOpsi = [];
                    if (data.opsi) {
                        try { parsedOpsi = JSON.parse(data.opsi); } catch(e) { parsedOpsi = []; }
                    }
                    data.parsedOpsi = parsedOpsi;
                    this.previewData = data;
                    this.openPreview = true;
                });
        },
        confirmDelete(id) {
            Swal.fire({
                title: 'Hapus Soal?',
                text: 'Data yang dihapus tidak dapat dikembalikan.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        },
        selectedIds: [],
        selectAll: false,
        toggleSelect(id) {
            if (this.selectedIds.includes(id)) {
                this.selectedIds = this.selectedIds.filter(i => i !== id);
            } else {
                this.selectedIds.push(id);
            }
        },
        toggleSelectAll() {
            this.selectAll = !this.selectAll;
            if (this.selectAll) {
                const ids = [];
                document.querySelectorAll('.soal-checkbox').forEach(cb => ids.push(parseInt(cb.value)));
                this.selectedIds = ids;
            } else {
                this.selectedIds = [];
            }
        },
    }
}
</script>
</x-slot:scripts>
</x-layouts.admin>
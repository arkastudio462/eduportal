<x-layouts.admin title="Berita | EduPortal">
    <x-slot:styles>
    <style>
        [x-cloak] { display: none !important; }
        #editor-container .ql-editor { min-height: 200px; }
    </style>
    </x-slot:styles>
<div x-data="{ selectedIds: [], selectAll: false, toggleSelect(id) { if (this.selectedIds.includes(id)) { this.selectedIds = this.selectedIds.filter(i => i !== id) } else { this.selectedIds.push(id) } }, toggleSelectAll() { this.selectAll = !this.selectAll; if (this.selectAll) { const ids = []; document.querySelectorAll('.berita-checkbox').forEach(cb => ids.push(parseInt(cb.value))); this.selectedIds = ids; } else { this.selectedIds = []; } } }">
<div x-data="beritaManager()" x-init="init()">
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
        <h2 class="font-headline-lg text-headline-lg text-primary">Berita</h2>
        <p class="text-on-surface-variant font-body-md">Kelola berita dan informasi sekolah</p>
    </div>
    <button @click="openCreate()" class="px-4 py-2.5 bg-primary text-on-primary rounded-lg flex items-center gap-2 font-label-md hover:bg-primary-container transition-all active:scale-95">
        <span class="material-symbols-outlined">add</span>
        Buat Berita
    </button>
</div>

<div class="flex items-center gap-2 mb-4">
    <a href="{{ route('admin.berita') }}" class="px-4 py-1.5 rounded-full text-sm font-label-md border transition-all {{ !request('trashed') ? 'bg-primary text-on-primary border-primary' : 'border-outline-variant hover:bg-surface-container' }}">Aktif</a>
    <a href="{{ route('admin.berita', ['trashed' => 1]) }}" class="px-4 py-1.5 rounded-full text-sm font-label-md border transition-all {{ request('trashed') ? 'bg-primary text-on-primary border-primary' : 'border-outline-variant hover:bg-surface-container' }}">Sampah</a>
</div>

<div x-show="selectedIds.length > 0" x-cloak class="mb-4 flex items-center gap-3 px-4 py-3 bg-surface-container rounded-xl border border-outline-variant">
    <span class="text-sm text-on-surface-variant" x-text="selectedIds.length + ' dipilih'"></span>
    <div class="flex gap-2 ml-auto">
        <form method="POST" :action="'{{ route('admin.berita.bulk-destroy') }}'" x-data @submit.prevent="Swal.fire({title:'Konfirmasi',text:'Hapus berita yang dipilih?',icon:'warning',showCancelButton:true,confirmButtonText:'Ya, hapus!',cancelButtonText:'Batal'}).then(r=>r.isConfirmed&&$el.submit())">
            @csrf
            <input type="hidden" name="ids" :value="JSON.stringify(selectedIds)">
            <button type="submit" class="px-4 py-1.5 bg-error text-white rounded-lg text-sm font-label-md hover:bg-error/80 transition-all">Hapus</button>
        </form>
        @if(request('trashed'))
        <form method="POST" :action="'{{ route('admin.berita.bulk-restore') }}'">
            @csrf
            <input type="hidden" name="ids" :value="JSON.stringify(selectedIds)">
            <button type="submit" class="px-4 py-1.5 bg-primary text-on-primary rounded-lg text-sm font-label-md hover:bg-primary-container transition-all">Pulihkan</button>
        </form>
        @endif
        <button @click="selectedIds = []; selectAll = false" class="px-4 py-1.5 border border-outline-variant rounded-lg text-sm font-label-md text-outline hover:bg-surface-container transition-all">Batal</button>
    </div>
</div>

<div class="bg-white rounded-xl border border-outline-variant card-shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-surface-container-low">
                <tr>
                    <th class="px-6 py-4 w-10"><input type="checkbox" @click="toggleSelectAll()" :checked="selectAll" class="rounded border-outline-variant"></th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Thumbnail</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Judul</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Kategori</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Tanggal</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Utama</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant">
                @forelse($semuaBerita as $berita)
                <tr class="hover:bg-surface-container transition-colors">
                    <td class="px-6 py-4 w-10"><input type="checkbox" :value="{{ $berita->id }}" x-model="selectedIds" class="berita-checkbox rounded border-outline-variant"></td>
                    <td class="px-6 py-4">
                        @if($berita->gambar)
                        <img src="{{ $berita->gambar }}" alt="" class="w-16 h-12 object-cover rounded-lg">
                        @else
                        <div class="w-16 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
                            <span class="material-symbols-outlined text-gray-400 !text-[20px]">image</span>
                        </div>
                        @endif
                    </td>
                    <td class="px-6 py-4 font-body-md font-semibold max-w-xs truncate">{{ $berita->judul }}</td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-0.5 rounded-full text-xs font-bold bg-secondary-fixed text-secondary uppercase">#{{ $berita->kategori }}</span>
                    </td>
                    <td class="px-6 py-4 text-sm">{{ $berita->tanggal->isoFormat('D MMM YYYY') }}</td>
                    <td class="px-6 py-4">
                        @if($berita->is_utama)
                        <span class="px-3 py-0.5 rounded-full text-xs font-bold bg-tertiary-fixed text-tertiary">Utama</span>
                        @else
                        <span class="text-outline text-sm">-</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <button @click="openEdit(@js([
                                    'id' => $berita->id,
                                    'judul' => $berita->judul,
                                    'konten' => $berita->konten,
                                    'kategori' => $berita->kategori,
                                    'is_utama' => $berita->is_utama,
                                    'gambar' => $berita->gambar,
                                    'tanggal' => $berita->tanggal?->format('Y-m-d'),
                                ]))" class="p-2 hover:bg-surface-container-low rounded-lg transition-colors">
                                <span class="material-symbols-outlined text-outline">edit</span>
                            </button>
                            <form method="POST" action="{{ route('admin.berita.destroy', $berita) }}" @submit.prevent="Swal.fire({title:'Konfirmasi',text:'Yakin ingin menghapus berita ini?',icon:'warning',showCancelButton:true,confirmButtonText:'Ya, hapus!',cancelButtonText:'Batal'}).then(r=>r.isConfirmed&&$el.submit())">
                                @csrf
                                @method('DELETE')
                                <button class="p-2 hover:bg-error/10 rounded-lg transition-colors">
                                    <span class="material-symbols-outlined text-error">delete</span>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td class="px-6 py-8 text-center text-on-surface-variant" colspan="7">Belum ada berita.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if($semuaBerita->hasPages())
<div class="flex justify-center mt-6">{{ $semuaBerita->links('vendor.pagination.custom') }}</div>
@endif

{{-- Modal --}}
<div x-show="openModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="fixed inset-0 bg-black/40" @click="closeModal"></div>
    <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-3xl p-8 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between mb-6">
            <h3 class="font-headline-sm text-headline-sm" x-text="editMode ? 'Edit Berita' : 'Buat Berita'"></h3>
            <button @click="closeModal" class="p-2 hover:bg-surface-container rounded-lg">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form :action="editMode ? '{{ route('admin.berita.update', '__ID__') }}'.replace('__ID__', editId) : '{{ route('admin.berita.store') }}'" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            <template x-if="editMode">
                <input type="hidden" name="_method" value="PUT">
            </template>

            <div class="space-y-2">
                <label class="font-label-md text-label-md text-on-surface-variant">Judul</label>
                <input x-model="form.judul" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2" placeholder="Judul berita" type="text" name="judul" required>
            </div>

            <div class="space-y-2">
                <label class="font-label-md text-label-md text-on-surface-variant">Konten</label>
                <input type="hidden" name="konten" id="konten-input">
                <div id="editor-container" class="bg-surface-container-low border border-outline-variant rounded-xl overflow-hidden" style="min-height: 250px;"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">Kategori</label>
                    <input x-model="form.kategori" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2" placeholder="Contoh: edukasi, kegiatan, prestasi" type="text" name="kategori" required>
                </div>
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">Tanggal</label>
                    <input x-model="form.tanggal" type="date" name="tanggal"
                        class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2">
                </div>
            </div>

            {{-- Drag & Drop Thumbnail --}}
<div class="space-y-2">
    <label class="font-label-md text-label-md text-on-surface-variant">Thumbnail</label>

    <input type="file" name="gambar" accept="image/jpeg,image/png,image/jpg,image/webp"
           x-ref="fileInput" @change="handleFileSelect($event)" class="hidden">

    <!-- Drag & Drop area, shown only when no image -->
    <template x-if="!gambarPreview && !existingGambar">
        <div @dragover.prevent="dragging = true"
             @dragleave.prevent="dragging = false"
             @drop.prevent="handleDrop($event)"
             class="relative border-2 border-dashed rounded-xl p-6 text-center transition-colors"
             :class="dragging ? 'border-secondary bg-orange-50' : 'border-outline-variant hover:border-gray-400'">
            <div @click="selectGambar()" class="cursor-pointer">
                <span class="material-symbols-outlined text-3xl text-gray-300 mb-2">cloud_upload</span>
                <p class="text-sm text-gray-500">Seret gambar ke sini atau <span class="text-primary font-semibold">klik untuk memilih</span></p>
                <p class="text-xs text-gray-400 mt-1">JPEG, PNG, WebP. Maks 2MB</p>
            </div>
        </div>
    </template>

    <!-- Preview area, shown when image exists -->
    <template x-if="gambarPreview || existingGambar">
        <div class="flex flex-col items-center gap-3">
            <div class="relative inline-block">
                <img :src="gambarPreview || existingGambar" class="max-h-40 rounded-lg mx-auto shadow-sm">
                <button type="button" @click.stop="removeGambar()"
                        class="absolute -top-2 -right-2 w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center hover:bg-red-600 transition-colors">
                    <span class="material-symbols-outlined !text-[14px]">close</span>
                </button>
            </div>
            <button type="button" @click="selectGambar()" class="text-xs text-primary font-semibold hover:underline">Ubah Gambar</button>
        </div>
    </template>
</div>

            <div class="flex items-center gap-3">
                <input x-model="form.is_utama" type="checkbox" name="is_utama" value="1" class="w-5 h-5 rounded border-outline-variant text-primary focus:ring-primary">
                <label class="font-label-md text-label-md text-on-surface-variant">Tampilkan sebagai berita utama</label>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="button" @click="closeModal" class="flex-1 py-3 border border-outline-variant rounded-xl font-label-md hover:bg-surface-container transition-all">Batal</button>
                <button type="submit" class="flex-1 py-3 bg-primary text-on-primary rounded-xl font-label-md hover:bg-primary-container transition-all" x-text="editMode ? 'Simpan' : 'Buat'"></button>
            </div>
        </form>
    </div>
</div>
</div>

<x-slot:scripts>
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('beritaManager', () => ({
        openModal: false,
        editMode: false,
        editId: null,
        form: { judul: '', kategori: '', tanggal: '', is_utama: false },
        gambarFile: null,
        gambarPreview: null,
        existingGambar: null,
        dragging: false,
        quill: null,

        init() {},

        async initQuill(html) {
            const container = document.getElementById('editor-container');
            if (!container) return;

            if (this.quill) {
                this.quill.clipboard.dangerouslyPasteHTML(html || '');
                document.getElementById('konten-input').value = this.quill.root.innerHTML;
                return;
            }

            const [_, { default: Quill }] = await Promise.all([import('quill/dist/quill.snow.css'), import('quill')]);
            this.quill = new Quill(container, {
                theme: 'snow',
                modules: {
                    toolbar: [
                        [{ 'header': [1, 2, 3, false] }],
                        ['bold', 'italic', 'underline', 'strike'],
                        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                        ['blockquote', 'code-block'],
                        ['link', 'image'],
                        [{ 'align': [] }],
                        ['clean'],
                    ],
                },
                placeholder: 'Tulis konten berita di sini...',
            });

            const toolbar = this.quill.getModule('toolbar');
            toolbar.addHandler('image', () => this.selectQuillImage());

            this.quill.root.addEventListener('drop', (e) => {
                const files = Array.from(e.dataTransfer.files).filter(f => f.type.startsWith('image/'));
                if (files.length) {
                    e.preventDefault();
                    this.uploadQuillImage(files[0]);
                }
            });

            this.quill.root.addEventListener('paste', (e) => {
                const files = Array.from(e.clipboardData.files).filter(f => f.type.startsWith('image/'));
                if (files.length) {
                    e.preventDefault();
                    this.uploadQuillImage(files[0]);
                }
            });

            this.quill.on('text-change', () => {
                document.getElementById('konten-input').value = this.quill.root.innerHTML;
            });

            this.quill.clipboard.dangerouslyPasteHTML(html || '');
            document.getElementById('konten-input').value = this.quill.root.innerHTML;
        },

        selectQuillImage() {
            const input = document.createElement('input');
            input.type = 'file';
            input.accept = 'image/jpeg,image/png,image/jpg,image/webp';
            input.click();
            input.onchange = () => {
                const file = input.files[0];
                if (file) this.uploadQuillImage(file);
            };
        },

        uploadQuillImage(file) {
            const formData = new FormData();
            formData.append('file', file);

            if (!window.axios) return;

            const range = this.quill.getSelection(true) || { index: this.quill.getLength() };

            this.quill.disable();

            window.axios.post('{{ route('admin.berita.upload-image') }}', formData, {
                headers: { 'Content-Type': 'multipart/form-data' },
            }).then(res => {
                this.quill.enable();
                this.quill.insertEmbed(range.index, 'image', res.data.url);
                this.quill.setSelection(range.index + 1);
            }).catch(() => {
                this.quill.enable();
            });
        },

        resetForm() {
            this.form = { judul: '', kategori: '', tanggal: '', is_utama: false };
            this.gambarFile = null;
            this.gambarPreview = null;
            this.existingGambar = null;
            this.dragging = false;
            document.getElementById('konten-input').value = '';
            if (this.quill) {
                this.quill.setText('');
            }
        },

        today() {
            return new Date().toISOString().split('T')[0];
        },

        openCreate() {
            this.resetForm();
            this.editMode = false;
            this.editId = null;
            this.form.tanggal = this.today();
            this.openModal = true;
            this.$nextTick(() => this.initQuill(''));
        },

        openEdit(data) {
            this.resetForm();
            this.editMode = true;
            this.editId = data.id;
            this.form = { judul: data.judul, kategori: data.kategori, tanggal: data.tanggal || this.today(), is_utama: data.is_utama };
            if (data.gambar) {
                this.existingGambar = data.gambar;
            }
            this.openModal = true;
            this.$nextTick(() => this.initQuill(data.konten));
        },

        closeModal() {
            this.openModal = false;
            this.resetForm();
        },

        handleFileSelect(event) {
            const file = event.target.files[0];
            if (file) this.setGambar(file);
        },

        selectGambar() {
            this.$refs.fileInput?.click();
        },

        handleDrop(event) {
            this.dragging = false;
            event.dataTransfer.clearData();
            const file = event.dataTransfer.files[0];
            if (file && file.type.startsWith('image/')) {
                this.setGambar(file);
                const input = this.$refs.fileInput;
                if (input) {
                    const dt = new DataTransfer();
                    dt.items.add(file);
                    input.files = dt.files;
                }
            }
        },

        setGambar(file) {
            this.gambarFile = file;
            this.existingGambar = null;
            const reader = new FileReader();
            reader.onload = (e) => this.gambarPreview = e.target.result;
            reader.readAsDataURL(file);
        },

        removeGambar() {
            this.gambarFile = null;
            this.gambarPreview = null;
            this.existingGambar = null;
            if (this.$refs.fileInput) this.$refs.fileInput.value = '';
        },
    }));
});
</script>
</x-slot:scripts>
</div>
</x-layouts.admin>

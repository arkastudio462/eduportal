<x-layouts.portal-guru title="Perpustakaan - Portal Guru">
<x-slot:styles>
<style>[x-cloak] { display: none !important; }</style>
</x-slot:styles>
<div x-data="{ openCreate: false, openEdit: false, editId: null, form: { judul: '', penulis: '', penerbit: '', kategori: '', tahun_terbit: '', isbn: '', deskripsi: '', stok: 1 } }">
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
        <h2 class="font-headline-lg text-headline-lg text-primary">Perpustakaan</h2>
        <p class="text-on-surface-variant font-body-md">Cari dan kelola koleksi buku perpustakaan</p>
    </div>
    <button @click="openCreate = true; form = { judul: '', penulis: '', penerbit: '', kategori: '', tahun_terbit: '', isbn: '', deskripsi: '', stok: 1 }" class="px-4 py-2.5 bg-primary text-on-primary rounded-lg flex items-center gap-2 font-label-md hover:bg-primary/90 transition-all active:scale-95">
        <span class="material-symbols-outlined">add</span>
        Tambah Buku
    </button>
</div>

<form method="GET" class="bg-white p-6 rounded-xl border border-outline-variant card-shadow mb-6">
    <div class="flex flex-col sm:flex-row gap-4">
        <div class="flex-1 relative">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline">search</span>
            <input type="text" name="search" value="{{ $search }}" placeholder="Cari judul, penulis, atau penerbit..."
                class="w-full pl-10 pr-4 py-2.5 bg-surface-container-low border border-outline-variant rounded-xl focus:outline-none focus:border-primary focus:ring-2">
        </div>
        <div class="sm:w-48">
            <select name="kategori" class="w-full px-4 py-2.5 bg-surface-container-low border border-outline-variant rounded-xl focus:outline-none focus:border-primary" onchange="this.form.submit()">
                <option value="">Semua Kategori</option>
                @foreach($kategoriList as $k)
                <option value="{{ $k }}" {{ $kategori == $k ? 'selected' : '' }}>{{ $k }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="px-6 py-2.5 bg-primary text-on-primary rounded-xl font-label-md hover:bg-primary/90 transition-all active:scale-95">Cari</button>
        @if($search || $kategori)
        <a href="{{ route('portal-guru.perpustakaan') }}" class="px-4 py-2.5 border border-outline-variant rounded-xl font-label-md hover:bg-surface-container transition-all flex items-center">Reset</a>
        @endif
    </div>
</form>

@if($buku->isEmpty())
<div class="bg-white rounded-xl border border-outline-variant card-shadow p-8 text-center">
    <span class="material-symbols-outlined text-5xl text-outline mb-4">library_books</span>
    <h3 class="font-headline-sm text-headline-sm text-on-surface mb-2">Tidak Ditemukan</h3>
    <p class="text-on-surface-variant">Buku tidak ditemukan. Tambah buku baru atau coba kata kunci lain.</p>
</div>
@else
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-gutter">
    @foreach($buku as $b)
    <div class="bg-white rounded-xl border border-outline-variant card-shadow overflow-hidden hover:shadow-lg transition-shadow">
        <div class="h-40 bg-gradient-to-br from-primary-fixed to-secondary-fixed flex items-center justify-center relative">
            <span class="material-symbols-outlined text-5xl text-primary/40" style="font-variation-settings: 'FILL' 1;">menu_book</span>
            <div class="absolute top-2 right-2 flex gap-1">
                <button @click="openEdit = true; editId = {{ $b->id }}; form = { judul: '{{ $b->judul }}', penulis: '{{ $b->penulis ?? '' }}', penerbit: '{{ $b->penerbit ?? '' }}', kategori: '{{ $b->kategori ?? '' }}', tahun_terbit: '{{ $b->tahun_terbit ?? '' }}', isbn: '{{ $b->isbn ?? '' }}', deskripsi: '{{ $b->deskripsi ?? '' }}', stok: {{ $b->stok }} }" class="p-1.5 bg-white/80 rounded-lg hover:bg-white transition-colors">
                    <span class="material-symbols-outlined text-primary text-[16px]">edit</span>
                </button>
                <form method="POST" action="{{ route('portal-guru.perpustakaan.destroy', $b->id) }}" @submit.prevent="Swal.fire({title:'Konfirmasi',text:'Hapus buku {{ $b->judul }}?',icon:'warning',showCancelButton:true,confirmButtonText:'Ya, hapus!',cancelButtonText:'Batal'}).then(r=>r.isConfirmed&&$el.submit())">
                    @csrf
                    @method('DELETE')
                    <button class="p-1.5 bg-white/80 rounded-lg hover:bg-white transition-colors">
                        <span class="material-symbols-outlined text-error text-[16px]">delete</span>
                    </button>
                </form>
            </div>
        </div>
        <div class="p-4">
            <h3 class="font-headline-sm text-headline-sm text-primary text-sm truncate">{{ $b->judul }}</h3>
            <p class="text-xs text-on-surface-variant mt-1 truncate">{{ $b->penulis ?? 'Tanpa penulis' }}</p>
            <div class="flex items-center gap-2 mt-2">
                @if($b->kategori)
                <span class="px-2 py-0.5 bg-secondary-fixed text-secondary rounded text-[10px] font-bold">{{ $b->kategori }}</span>
                @endif
                <span class="text-xs text-outline">Stok: <strong>{{ $b->stok }}</strong></span>
            </div>
            @if($b->penerbit || $b->tahun_terbit)
            <p class="text-[10px] text-outline mt-2">{{ $b->penerbit ?? '' }}{{ $b->penerbit && $b->tahun_terbit ? ', ' : '' }}{{ $b->tahun_terbit ?? '' }}</p>
            @endif
        </div>
    </div>
    @endforeach
</div>
<div class="mt-6">
    {{ $buku->withQueryString()->links('vendor.pagination.custom') ?? '' }}
</div>
@endif

<!-- Create Modal -->
<div x-show="openCreate" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" @click.away="openCreate = false">
    <div class="fixed inset-0 bg-black/40" @click="openCreate = false"></div>
    <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-lg p-8">
        <div class="flex items-center justify-between mb-6">
            <h3 class="font-headline-sm text-headline-sm">Tambah Buku Baru</h3>
            <button @click="openCreate = false" class="p-2 hover:bg-surface-container rounded-lg"><span class="material-symbols-outlined">close</span></button>
        </div>
        <form action="{{ route('portal-guru.perpustakaan.store') }}" method="POST" class="space-y-5">
            @csrf
            <div class="space-y-2">
                <label class="font-label-md text-label-md text-on-surface-variant">Judul</label>
                <input x-model="form.judul" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3" type="text" name="judul" required>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">Penulis</label>
                    <input x-model="form.penulis" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3" type="text" name="penulis">
                </div>
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">Penerbit</label>
                    <input x-model="form.penerbit" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3" type="text" name="penerbit">
                </div>
            </div>
            <div class="grid grid-cols-3 gap-4">
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">Kategori</label>
                    <input x-model="form.kategori" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3" type="text" name="kategori" list="kategori-list">
                    <datalist id="kategori-list">
                        @foreach($kategoriList as $k)
                        <option value="{{ $k }}">
                        @endforeach
                    </datalist>
                </div>
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">Tahun</label>
                    <input x-model="form.tahun_terbit" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3" type="number" name="tahun_terbit">
                </div>
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">Stok</label>
                    <input x-model="form.stok" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3" type="number" name="stok" min="0">
                </div>
            </div>
            <div class="space-y-2">
                <label class="font-label-md text-label-md text-on-surface-variant">ISBN</label>
                <input x-model="form.isbn" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3" type="text" name="isbn">
            </div>
            <div class="space-y-2">
                <label class="font-label-md text-label-md text-on-surface-variant">Deskripsi</label>
                <textarea x-model="form.deskripsi" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3" rows="3" name="deskripsi"></textarea>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="button" @click="openCreate = false" class="flex-1 py-3 border border-outline-variant rounded-xl font-label-md">Batal</button>
                <button type="submit" class="flex-1 py-3 bg-primary text-on-primary rounded-xl font-label-md">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div x-show="openEdit" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" @click.away="openEdit = false">
    <div class="fixed inset-0 bg-black/40" @click="openEdit = false"></div>
    <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-lg p-8">
        <div class="flex items-center justify-between mb-6">
            <h3 class="font-headline-sm text-headline-sm">Edit Buku</h3>
            <button @click="openEdit = false" class="p-2 hover:bg-surface-container rounded-lg"><span class="material-symbols-outlined">close</span></button>
        </div>
        <form method="POST" action="{{ route('portal-guru.perpustakaan.update', '__ID__') }}" x-bind:action="'{{ route('portal-guru.perpustakaan.update', '__ID__') }}'.replace('__ID__', editId)" class="space-y-5">
            @csrf
            @method('PUT')
            <div class="space-y-2">
                <label class="font-label-md text-label-md text-on-surface-variant">Judul</label>
                <input x-model="form.judul" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3" type="text" name="judul" required>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">Penulis</label>
                    <input x-model="form.penulis" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3" type="text" name="penulis">
                </div>
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">Penerbit</label>
                    <input x-model="form.penerbit" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3" type="text" name="penerbit">
                </div>
            </div>
            <div class="grid grid-cols-3 gap-4">
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">Kategori</label>
                    <input x-model="form.kategori" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3" type="text" name="kategori">
                </div>
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">Tahun</label>
                    <input x-model="form.tahun_terbit" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3" type="number" name="tahun_terbit">
                </div>
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">Stok</label>
                    <input x-model="form.stok" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3" type="number" name="stok" min="0">
                </div>
            </div>
            <div class="space-y-2">
                <label class="font-label-md text-label-md text-on-surface-variant">ISBN</label>
                <input x-model="form.isbn" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3" type="text" name="isbn">
            </div>
            <div class="space-y-2">
                <label class="font-label-md text-label-md text-on-surface-variant">Deskripsi</label>
                <textarea x-model="form.deskripsi" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3" rows="3" name="deskripsi"></textarea>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="button" @click="openEdit = false" class="flex-1 py-3 border border-outline-variant rounded-xl font-label-md">Batal</button>
                <button type="submit" class="flex-1 py-3 bg-primary text-on-primary rounded-xl font-label-md">Simpan</button>
            </div>
        </form>
    </div>
    </div>
</div>
</x-layouts.portal-guru>

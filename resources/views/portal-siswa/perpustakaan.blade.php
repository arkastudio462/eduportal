<x-layouts.portal-siswa title="Perpustakaan - Portal Siswa">
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
        <h2 class="font-headline-lg text-headline-lg text-primary">Perpustakaan</h2>
        <p class="text-on-surface-variant font-body-md">Cari dan jelajahi koleksi buku perpustakaan</p>
    </div>
</div>

@if(isset($peminjaman) && count($peminjaman) > 0)
<div class="bg-white rounded-xl border border-outline-variant card-shadow mb-8">
    <div class="p-6 border-b border-outline-variant">
        <h3 class="font-headline-sm text-headline-sm text-primary">Riwayat Peminjaman Anda</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-surface-container-low text-on-surface-variant font-label-md text-sm">
                    <th class="p-4 border-b border-outline-variant">Buku</th>
                    <th class="p-4 border-b border-outline-variant">Tanggal Pinjam</th>
                    <th class="p-4 border-b border-outline-variant">Tanggal Kembali</th>
                    <th class="p-4 border-b border-outline-variant">Status</th>
                </tr>
            </thead>
            <tbody class="text-sm">
                @foreach($peminjaman as $pinjam)
                <tr class="border-b border-outline-variant hover:bg-surface/50">
                    <td class="p-4 font-medium text-on-surface">{{ $pinjam->buku->judul ?? 'Buku dihapus' }}</td>
                    <td class="p-4 text-on-surface-variant">{{ \Carbon\Carbon::parse($pinjam->tanggal_pinjam)->isoFormat('D MMM YYYY') }}</td>
                    <td class="p-4 text-on-surface-variant">
                        @if($pinjam->tanggal_kembali)
                            {{ \Carbon\Carbon::parse($pinjam->tanggal_kembali)->isoFormat('D MMM YYYY') }}
                        @else
                            <span class="text-outline italic">-</span>
                        @endif
                    </td>
                    <td class="p-4">
                        @if($pinjam->status == 'dipinjam')
                            <span class="px-2 py-1 rounded-full bg-error/10 text-error text-xs font-bold uppercase">Dipinjam</span>
                        @elseif($pinjam->status == 'dikembalikan')
                            <span class="px-2 py-1 rounded-full bg-primary/10 text-primary text-xs font-bold uppercase">Dikembalikan</span>
                        @else
                            <span class="px-2 py-1 rounded-full bg-surface-container text-on-surface-variant text-xs font-bold uppercase">{{ $pinjam->status }}</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

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
        <a href="{{ route('portal-siswa.perpustakaan') }}" class="px-4 py-2.5 border border-outline-variant rounded-xl font-label-md hover:bg-surface-container transition-all flex items-center">Reset</a>
        @endif
    </div>
</form>

@if($buku->isEmpty())
<div class="bg-white rounded-xl border border-outline-variant card-shadow p-8 text-center">
    <span class="material-symbols-outlined text-5xl text-outline mb-4">library_books</span>
    <h3 class="font-headline-sm text-headline-sm text-on-surface mb-2">Tidak Ditemukan</h3>
    <p class="text-on-surface-variant">Buku tidak ditemukan. Coba kata kunci lain.</p>
</div>
@else
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-gutter">
    @foreach($buku as $b)
    <div class="bg-white rounded-xl border border-outline-variant card-shadow overflow-hidden hover:shadow-lg transition-shadow">
        <div class="h-40 bg-gradient-to-br from-primary-fixed to-secondary-fixed flex items-center justify-center">
            <span class="material-symbols-outlined text-5xl text-primary/40" style="font-variation-settings: 'FILL' 1;">menu_book</span>
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
</x-layouts.portal-siswa>

<x-layouts.portal-siswa title="Ekstrakurikuler">
<x-slot:styles>
<style>[x-cloak] { display: none !important; }</style>
</x-slot:styles>
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
        <h2 class="font-headline-lg text-headline-lg text-primary">Ekstrakurikuler</h2>
        <p class="text-on-surface-variant font-body-md">Ikuti kegiatan ekstrakurikuler sesuai minat</p>
    </div>
</div>

@if($myEkskuls->isNotEmpty())
<div class="mb-8">
    <h3 class="font-headline-md text-headline-md text-primary mb-4">Ekskul Saya</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @foreach($myEkskuls as $ekskul)
        <div class="bg-secondary-container/20 rounded-xl border border-secondary/30 card-shadow p-4">
            <div class="flex items-start justify-between">
                <div>
                    <h4 class="font-label-md text-label-md text-primary">{{ $ekskul->nama }}</h4>
                    <p class="text-xs text-on-surface-variant mt-1">{{ $ekskul->pembina }} • {{ $ekskul->hari }} {{ substr($ekskul->jam_mulai, 0, 5) }}-{{ substr($ekskul->jam_selesai, 0, 5) }}</p>
                </div>
                <form method="POST" action="{{ route('portal-siswa.ekskul.leave', $ekskul) }}">
                    @csrf @method('DELETE')
                    <button type="submit" class="text-xs text-error hover:underline">Keluar</button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

<div>
    <h3 class="font-headline-md text-headline-md text-primary mb-4">Daftar Ekskul</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($ekskuls as $ekskul)
        <div class="bg-white rounded-xl border border-outline-variant card-shadow p-5 hover:translate-y-[-4px] transition-transform duration-200">
            <div class="flex items-start gap-3 mb-3">
                <div class="w-10 h-10 rounded-lg bg-primary text-on-primary flex items-center justify-center flex-shrink-0">
                    <span class="material-symbols-outlined text-xl">sports</span>
                </div>
                <div class="min-w-0 flex-1">
                    <h4 class="font-label-md text-label-md text-primary truncate">{{ $ekskul->nama }}</h4>
                    <p class="text-xs text-on-surface-variant">{{ $ekskul->pembina }}</p>
                </div>
            </div>
            <div class="space-y-1 text-xs text-on-surface-variant mb-3">
                <p><span class="font-medium">Jadwal:</span> {{ $ekskul->hari }}, {{ substr($ekskul->jam_mulai, 0, 5) }}-{{ substr($ekskul->jam_selesai, 0, 5) }}</p>
                <p><span class="font-medium">Tempat:</span> {{ $ekskul->tempat }}</p>
                <p><span class="font-medium">Anggota:</span> {{ $ekskul->anggota_count }}/{{ $ekskul->kuota }}</p>
            </div>
            @if($ekskul->deskripsi)
            <p class="text-xs text-on-surface-variant mb-3 line-clamp-2">{{ $ekskul->deskripsi }}</p>
            @endif
            @php $isMember = $myEkskuls->contains('id', $ekskul->id); @endphp
            @if($isMember)
                <span class="inline-flex items-center gap-1 px-3 py-1.5 bg-green-100 text-green-700 rounded-lg text-xs font-medium">Terdaftar</span>
            @elseif($ekskul->anggota_count >= $ekskul->kuota)
                <span class="inline-flex items-center gap-1 px-3 py-1.5 bg-outline/10 text-outline rounded-lg text-xs font-medium">Penuh</span>
            @else
                <a href="{{ route('portal-siswa.ekskul.join', $ekskul) }}" class="inline-flex items-center gap-1 px-3 py-1.5 bg-primary text-on-primary rounded-lg text-xs hover:bg-primary-container transition-colors">Daftar</a>
            @endif
        </div>
        @empty
        <div class="col-span-full bg-white rounded-xl border border-outline-variant card-shadow p-8 text-center">
            <span class="material-symbols-outlined text-4xl text-outline mb-2">sports_kabaddi</span>
            <p class="font-body-md text-outline">Belum ada ekskul tersedia</p>
        </div>
        @endforelse
    </div>
</div>
</x-layouts.portal-siswa>

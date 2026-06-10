<x-layouts.admin title="Tracer Study | EduPortal">
<x-slot:styles>
<style>[x-cloak] { display: none !important; }</style>
</x-slot:styles>

<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
        <h2 class="font-headline-lg text-headline-lg text-primary">Tracer Study</h2>
        <p class="text-on-surface-variant font-body-md">Data alumni yang mengisi tracer study</p>
    </div>
</div>

<div class="bg-white rounded-xl border border-outline-variant card-shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-surface-container-low text-left">
                    <th class="px-6 py-4 font-label-md text-label-md text-on-surface-variant">Nama</th>
                    <th class="px-6 py-4 font-label-md text-label-md text-on-surface-variant">Tahun Lulus</th>
                    <th class="px-6 py-4 font-label-md text-label-md text-on-surface-variant">Pekerjaan</th>
                    <th class="px-6 py-4 font-label-md text-label-md text-on-surface-variant">Universitas</th>
                    <th class="px-6 py-4 font-label-md text-label-md text-on-surface-variant">Kontak</th>
                    <th class="px-6 py-4 font-label-md text-label-md text-on-surface-variant">Tanggal</th>
                    <th class="px-6 py-4 font-label-md text-label-md text-on-surface-variant">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tracerStudy as $entry)
                <tr class="border-t border-outline-variant hover:bg-surface-container-low transition-colors">
                    <td class="px-6 py-4">{{ $entry->nama }}</td>
                    <td class="px-6 py-4">{{ $entry->tahun_lulus }}</td>
                    <td class="px-6 py-4">{{ $entry->pekerjaan ?: '-' }}</td>
                    <td class="px-6 py-4">{{ $entry->universitas ?: '-' }}</td>
                    <td class="px-6 py-4">{!! $entry->kontak ? nl2br(e($entry->kontak)) : '-' !!}</td>
                    <td class="px-6 py-4">{{ $entry->created_at->isoFormat('D MMM YYYY HH:mm') }}</td>
                    <td class="px-6 py-4">
                        <button x-data @click="$dispatch('open-detail-ts', { nama: '{{ $entry->nama }}', tahun_lulus: '{{ $entry->tahun_lulus }}', pekerjaan: '{{ $entry->pekerjaan }}', universitas: '{{ $entry->universitas }}', kontak: '{{ $entry->kontak }}', pesan: '{{ $entry->pesan }}' })" class="p-2 hover:bg-surface-container-low rounded-lg">
                            <span class="material-symbols-outlined text-outline">visibility</span>
                        </button>
                        <form method="POST" action="{{ route('admin.tracer-study.destroy', $entry) }}" x-data @submit.prevent="Swal.fire({title:'Konfirmasi',text:'Yakin ingin menghapus data ini?',icon:'warning',showCancelButton:true,confirmButtonText:'Ya, hapus!',cancelButtonText:'Batal'}).then(r=>r.isConfirmed&&$el.submit())" class="inline">
                            @csrf
                            @method('DELETE')
                            <button class="p-2 hover:bg-error/10 rounded-lg">
                                <span class="material-symbols-outlined text-error">delete</span>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-on-surface-variant">Belum ada data tracer study.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($tracerStudy->hasPages())
    <div class="p-4 border-t border-outline-variant">
        {{ $tracerStudy->links() }}
    </div>
    @endif
</div>

<!-- Detail Modal -->
<div x-data="{ open: false, entry: {} }" @open-detail-ts.window="open = true; entry = $event.detail">
    <div x-show="open" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" @click.away="open = false">
        <div class="fixed inset-0 bg-black/40" @click="open = false"></div>
        <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-lg p-8">
            <div class="flex items-center justify-between mb-6">
                <h3 class="font-headline-sm text-headline-sm">Detail Tracer Study</h3>
                <button @click="open = false" class="p-2 hover:bg-surface-container rounded-lg"><span class="material-symbols-outlined">close</span></button>
            </div>
            <div class="space-y-4">
                <div>
                    <p class="font-label-md text-label-md text-on-surface-variant">Nama</p>
                    <p x-text="entry.nama" class="font-body-md"></p>
                </div>
                <div>
                    <p class="font-label-md text-label-md text-on-surface-variant">Tahun Lulus</p>
                    <p x-text="entry.tahun_lulus" class="font-body-md"></p>
                </div>
                <div>
                    <p class="font-label-md text-label-md text-on-surface-variant">Pekerjaan</p>
                    <p x-text="entry.pekerjaan || '-'" class="font-body-md"></p>
                </div>
                <div>
                    <p class="font-label-md text-label-md text-on-surface-variant">Universitas</p>
                    <p x-text="entry.universitas || '-'" class="font-body-md"></p>
                </div>
                <div>
                    <p class="font-label-md text-label-md text-on-surface-variant">Kontak</p>
                    <p x-text="entry.kontak || '-'" class="font-body-md"></p>
                </div>
                <div>
                    <p class="font-label-md text-label-md text-on-surface-variant">Pesan</p>
                    <p x-text="entry.pesan || '-'" class="font-body-md bg-surface-container-low rounded-xl p-4"></p>
                </div>
            </div>
        </div>
    </div>
</div>
</x-layouts.admin>

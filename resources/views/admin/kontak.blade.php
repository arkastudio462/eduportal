<x-layouts.admin title="Pesan Masuk | EduPortal">
<x-slot:styles>
<style>[x-cloak] { display: none !important; }</style>
</x-slot:styles>

<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
        <h2 class="font-headline-lg text-headline-lg text-primary">Pesan Masuk</h2>
        <p class="text-on-surface-variant font-body-md">Pesan dari pengunjung website</p>
    </div>
</div>

<div class="bg-white rounded-xl border border-outline-variant card-shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-surface-container-low text-left">
                    <th class="px-6 py-4 font-label-md text-label-md text-on-surface-variant">Status</th>
                    <th class="px-6 py-4 font-label-md text-label-md text-on-surface-variant">Nama</th>
                    <th class="px-6 py-4 font-label-md text-label-md text-on-surface-variant">Email</th>
                    <th class="px-6 py-4 font-label-md text-label-md text-on-surface-variant">Subjek</th>
                    <th class="px-6 py-4 font-label-md text-label-md text-on-surface-variant">Tanggal</th>
                    <th class="px-6 py-4 font-label-md text-label-md text-on-surface-variant">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($messages as $message)
                <tr class="border-t border-outline-variant hover:bg-surface-container-low transition-colors {{ !$message->dibaca ? 'font-bold' : '' }}">
                    <td class="px-6 py-4">
                        @if($message->dibaca)
                            <span class="px-2 py-1 rounded-full text-xs bg-surface-container text-on-surface-variant">Sudah dibaca</span>
                        @else
                            <span class="px-2 py-1 rounded-full text-xs bg-warning text-white">Baru</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">{{ $message->nama }}</td>
                    <td class="px-6 py-4">{{ $message->email }}</td>
                    <td class="px-6 py-4 max-w-[200px] truncate">{{ $message->subjek }}</td>
                    <td class="px-6 py-4">{{ $message->created_at->isoFormat('D MMM YYYY HH:mm') }}</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <button x-data @click="$dispatch('open-detail', { id: {{ $message->id }} })" class="p-2 hover:bg-surface-container-low rounded-lg">
                                <span class="material-symbols-outlined text-outline">visibility</span>
                            </button>
                            @if(!$message->dibaca)
                            <form method="POST" action="{{ route('admin.kontak.mark-read', $message) }}" class="inline">
                                @csrf
                                <button class="p-2 hover:bg-surface-container-low rounded-lg">
                                    <span class="material-symbols-outlined text-outline">mark_email_read</span>
                                </button>
                            </form>
                            @endif
                            <form method="POST" action="{{ route('admin.kontak.destroy', $message) }}" x-data @submit.prevent="Swal.fire({title:'Konfirmasi',text:'Yakin ingin menghapus pesan ini?',icon:'warning',showCancelButton:true,confirmButtonText:'Ya, hapus!',cancelButtonText:'Batal'}).then(r=>r.isConfirmed&&$el.submit())" class="inline">
                                @csrf
                                @method('DELETE')
                                <button class="p-2 hover:bg-error/10 rounded-lg">
                                    <span class="material-symbols-outlined text-error">delete</span>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-on-surface-variant">Belum ada pesan masuk.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($messages->hasPages())
    <div class="p-4 border-t border-outline-variant">
        {{ $messages->links() }}
    </div>
    @endif
</div>

<!-- Detail Modal -->
<div x-data="{ open: false, message: {} }" @open-detail.window="open = true; fetch('/admin/kontak/' + $event.detail.id).then(r => r.json()).then(d => message = d)">
    <div x-show="open" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" @click.away="open = false">
        <div class="fixed inset-0 bg-black/40" @click="open = false"></div>
        <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-lg p-8">
            <div class="flex items-center justify-between mb-6">
                <h3 class="font-headline-sm text-headline-sm">Detail Pesan</h3>
                <button @click="open = false" class="p-2 hover:bg-surface-container rounded-lg"><span class="material-symbols-outlined">close</span></button>
            </div>
            <div class="space-y-4" x-show="message.id">
                <div>
                    <p class="font-label-md text-label-md text-on-surface-variant">Nama</p>
                    <p x-text="message.nama" class="font-body-md"></p>
                </div>
                <div>
                    <p class="font-label-md text-label-md text-on-surface-variant">Email</p>
                    <p x-text="message.email" class="font-body-md"></p>
                </div>
                <div>
                    <p class="font-label-md text-label-md text-on-surface-variant">Subjek</p>
                    <p x-text="message.subjek" class="font-body-md"></p>
                </div>
                <div>
                    <p class="font-label-md text-label-md text-on-surface-variant">Pesan</p>
                    <p x-text="message.pesan" class="font-body-md bg-surface-container-low rounded-xl p-4"></p>
                </div>
            </div>
        </div>
    </div>
</div>
</x-layouts.admin>

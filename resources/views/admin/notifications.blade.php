<x-layouts.admin title="Notifikasi | EduPortal">
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
        <h2 class="font-headline-lg text-headline-lg text-primary">Notifikasi</h2>
        <p class="text-on-surface-variant font-body-md">Semua pemberitahuan dan informasi terbaru</p>
    </div>
    @if(Auth::user()->unreadNotifications->count())
    <form method="POST" action="{{ route('admin.notifications.read-all') }}">
        @csrf
        <button type="submit" class="px-4 py-2.5 bg-secondary-container text-on-secondary-container rounded-lg font-label-md hover:bg-secondary-container/70 transition-all">Tandai Semua Dibaca</button>
    </form>
    @endif
</div>

<div class="bg-white rounded-xl border border-outline-variant card-shadow overflow-hidden">
    @forelse($notifications as $n)
    <div class="p-5 border-b border-outline-variant hover:bg-surface-container/30 transition-colors {{ $n->read_at ? '' : 'bg-primary-fixed/10' }}">
        <div class="flex items-start gap-4">
            <div class="w-10 h-10 rounded-full {{ $n->read_at ? 'bg-surface-container' : 'bg-primary-fixed' }} flex items-center justify-center shrink-0">
                <span class="material-symbols-outlined {{ $n->read_at ? 'text-outline' : 'text-primary' }}" style="font-variation-settings: 'FILL' 1;">
                    {{ ($n->data['type'] ?? '') == 'tugas' ? 'assignment' : 'campaign' }}
                </span>
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex items-start justify-between gap-2">
                    <div>
                        <h4 class="font-label-md text-label-md {{ $n->read_at ? 'text-on-surface' : 'text-primary font-bold' }}">{{ $n->data['judul'] ?? 'Notifikasi' }}</h4>
                        @if(isset($n->data['mapel']))
                        <p class="text-xs text-on-surface-variant">Mata Pelajaran: {{ $n->data['mapel'] }}</p>
                        @endif
                    </div>
                    <span class="text-xs text-outline shrink-0">{{ $n->created_at->diffForHumans() }}</span>
                </div>
                @if(isset($n->data['konten']))
                <p class="text-sm text-on-surface-variant mt-1 line-clamp-2">{{ $n->data['konten'] }}</p>
                @endif
            </div>
            @if(!$n->read_at)
            <form method="POST" action="{{ route('admin.notifications.read', $n->id) }}">
                @csrf
                <button type="submit" class="shrink-0 p-2 hover:bg-surface-container rounded-lg">
                    <span class="material-symbols-outlined text-outline text-lg">chevron_right</span>
                </button>
            </form>
            @endif
        </div>
    </div>
    @empty
    <div class="p-8 text-center">
        <span class="material-symbols-outlined text-5xl text-outline mb-4">notifications_none</span>
        <h3 class="font-headline-sm text-headline-sm text-on-surface mb-2">Tidak Ada Notifikasi</h3>
        <p class="text-on-surface-variant">Belum ada pemberitahuan baru.</p>
    </div>
    @endforelse
</div>

<div class="mt-6">{{ $notifications->links('vendor.pagination.custom') ?? $notifications->links() }}</div>
</x-layouts.admin>

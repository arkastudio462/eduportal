<x-layouts.portal-siswa title="Pesan">
<x-slot:styles>
<style>[x-cloak] { display: none !important; }</style>
</x-slot:styles>
<div x-data="{ showNew: false }">
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
        <h2 class="font-headline-lg text-headline-lg text-primary">Pesan Internal</h2>
        <p class="text-on-surface-variant font-body-md">Komunikasi dengan guru</p>
    </div>
    <button @click="showNew = true" class="inline-flex items-center gap-2 bg-primary text-on-primary px-4 py-2 rounded-xl font-label-md hover:bg-primary-container transition-colors">
        <span class="material-symbols-outlined">add</span>
        Pesan Baru
    </button>
</div>
    <div x-show="showNew" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/40" @click.self="showNew = false">
        <div class="bg-white rounded-2xl p-6 w-full max-w-lg mx-4 shadow-xl">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-headline-md text-headline-md text-primary">Pesan Baru</h3>
                <button @click="showNew = false" class="text-outline hover:text-on-surface"><span class="material-symbols-outlined">close</span></button>
            </div>
            <form method="POST" action="{{ route('portal-siswa.pesan.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="font-label-sm text-label-sm text-outline mb-1 block">Kepada</label>
                    <select name="recipient_id" required class="w-full px-3 py-2 border border-outline-variant rounded-lg text-sm focus:ring-2 focus:ring-primary focus:border-primary outline-none bg-white">
                        <option value="">Pilih penerima</option>
                        @foreach(\App\Models\User::where('id', '!=', Auth::id())->get() as $user)
                        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->role }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="font-label-sm text-label-sm text-outline mb-1 block">Subjek</label>
                    <input type="text" name="subjek" class="w-full px-3 py-2 border border-outline-variant rounded-lg text-sm focus:ring-2 focus:ring-primary focus:border-primary outline-none">
                </div>
                <div>
                    <label class="font-label-sm text-label-sm text-outline mb-1 block">Pesan</label>
                    <textarea name="body" required rows="4" class="w-full px-3 py-2 border border-outline-variant rounded-lg text-sm focus:ring-2 focus:ring-primary focus:border-primary outline-none"></textarea>
                </div>
                <button type="submit" class="w-full bg-primary text-on-primary py-2.5 rounded-xl font-label-md hover:bg-primary-container transition-colors">Kirim</button>
            </form>
        </div>
    </div>
</div>

@if($conversations->isEmpty())
<div class="bg-white rounded-xl border border-outline-variant card-shadow p-8 text-center">
    <span class="material-symbols-outlined text-4xl text-outline mb-2">chat</span>
    <p class="font-body-md text-outline">Belum ada percakapan</p>
</div>
@else
<div class="bg-white rounded-xl border border-outline-variant card-shadow overflow-hidden">
    @foreach($conversations as $conv)
    <a href="{{ route('portal-siswa.pesan.show', $conv) }}" class="flex items-center gap-4 p-4 border-b border-outline-variant hover:bg-surface-bright transition-colors last:border-b-0">
        <div class="w-10 h-10 rounded-full bg-primary text-on-primary flex items-center justify-center font-bold text-sm flex-shrink-0">
            {{ $conv->participants->where('id', '!=', Auth::id())->first()?->name[0] ?? '?' }}
        </div>
        <div class="flex-1 min-w-0">
            <p class="font-label-md text-label-md truncate">{{ $conv->subjek ?? 'Tanpa Subjek' }}</p>
            <p class="text-xs text-on-surface-variant truncate">
                {{ $conv->participants->where('id', '!=', Auth::id())->first()?->name ?? 'Unknown' }}
            </p>
        </div>
        <div class="text-xs text-on-surface-variant whitespace-nowrap">
            {{ $conv->last_message_at?->diffForHumans() ?? $conv->created_at->diffForHumans() }}
        </div>
    </a>
    @endforeach
</div>
@endif
</x-layouts.portal-siswa>

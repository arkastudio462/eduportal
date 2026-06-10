<x-layouts.admin title="Backup Database | EduPortal">
<x-slot:styles>
<style>[x-cloak] { display: none !important; }</style>
</x-slot:styles>
<div x-data="{ deleting: null }">
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
        <h2 class="font-headline-lg text-headline-lg text-primary">Backup Database</h2>
        <p class="text-on-surface-variant font-body-md">Kelola backup database SQLite</p>
    </div>
    <form action="{{ route('admin.backup.create') }}" method="POST">
        @csrf
        <button type="submit"
                class="px-4 py-2.5 bg-primary text-on-primary rounded-lg flex items-center gap-2 font-label-md hover:bg-primary/90 transition-all active:scale-95 cursor-pointer">
            <span class="material-symbols-outlined">backup</span>
            Backup Sekarang
        </button>
    </form>
</div>

<div class="bg-white rounded-xl border border-outline-variant card-shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-primary text-on-primary text-xs uppercase tracking-wider">
                <tr>
                    <th class="px-6 py-4 text-left">Nama File</th>
                    <th class="px-6 py-4 text-left">Ukuran</th>
                    <th class="px-6 py-4 text-left">Tanggal</th>
                    <th class="px-6 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant">
                @forelse($backups as $backup)
                <tr class="hover:bg-surface-container-low transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-outline">database</span>
                            <span class="font-semibold">{{ $backup['name'] }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-on-surface-variant">
                        @php $size = $backup['size']; @endphp
                        {{ $size > 1048576 ? round($size / 1048576, 2).' MB' : ($size > 1024 ? round($size / 1024, 2).' KB' : $size.' B') }}
                    </td>
                    <td class="px-6 py-4 text-on-surface-variant">
                        {{ \Carbon\Carbon::createFromTimestamp($backup['last_modified'])->format('d M Y H:i') }}
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.backup.download', $backup['name']) }}"
                               class="p-2 hover:bg-surface-container rounded-lg transition-colors" title="Download">
                                <span class="material-symbols-outlined text-outline">download</span>
                            </a>
                            <form action="{{ route('admin.backup.restore', $backup['name']) }}" method="POST"
                                  onsubmit="return confirm('Restore akan mengganti database saat ini. Lanjutkan?')"
                                  class="inline">
                                @csrf
                                <button type="submit" class="p-2 hover:bg-surface-container rounded-lg transition-colors" title="Restore">
                                    <span class="material-symbols-outlined text-amber-600">restore</span>
                                </button>
                            </form>
                            <form action="{{ route('admin.backup.destroy', $backup['name']) }}" method="POST"
                                  onsubmit="return confirm('Hapus backup ini?')"
                                  class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-2 hover:bg-surface-container rounded-lg transition-colors" title="Hapus">
                                    <span class="material-symbols-outlined text-error">delete</span>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-12 text-center text-on-surface-variant">
                        <span class="material-symbols-outlined text-4xl mb-2 block">backup</span>
                        <p>Belum ada backup. Klik "Backup Sekarang" untuk membuat backup pertama.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
</div>
</x-layouts.admin>

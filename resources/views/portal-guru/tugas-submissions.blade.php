<x-layouts.portal-guru title="Pengumpulan Tugas - Portal Guru">
<div class="flex items-center gap-4 mb-8">
    <a href="{{ route('portal-guru.tugas') }}" class="p-2 hover:bg-surface-container rounded-lg transition-colors">
        <span class="material-symbols-outlined">arrow_back</span>
    </a>
    <div>
        <h2 class="font-headline-lg text-headline-lg text-primary">{{ $tugas->judul }}</h2>
        <p class="text-on-surface-variant font-body-md">{{ $tugas->mapel->nama ?? '-' }} • {{ $tugas->kelas->nama ?? '-' }}</p>
    </div>
</div>

<div class="bg-white rounded-xl border border-outline-variant card-shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-surface-container-low">
                <tr>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">No</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Nama Siswa</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Tanggal Kumpul</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Catatan</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Nilai</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant">
                @forelse($tugas->submissions as $index => $sub)
                <tr class="hover:bg-surface-container transition-colors">
                    <td class="px-6 py-4">{{ $index + 1 }}</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-primary-fixed text-primary rounded-full flex items-center justify-center font-bold text-xs">{{ substr($sub->siswa->user->name, 0, 1) }}</div>
                            <span class="font-body-md font-semibold">{{ $sub->siswa->user->name }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm">{{ $sub->created_at->isoFormat('D MMM YYYY HH:mm') }}</td>
                    <td class="px-6 py-4 text-sm text-on-surface-variant max-w-[200px] truncate">{{ $sub->catatan ?? '-' }}</td>
                    <td class="px-6 py-4">
                        <span class="font-bold {{ $sub->nilai && $sub->nilai >= 75 ? 'text-green-600' : ($sub->nilai ? 'text-error' : 'text-outline') }}">
                            {{ $sub->nilai ?? '-' }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <form method="POST" action="{{ route('portal-guru.tugas.nilai', [$tugas->id, $sub->id]) }}" class="flex items-center gap-2">
                            @csrf
                            <input type="number" name="nilai" value="{{ $sub->nilai ?? '' }}" class="w-20 px-2 py-1.5 border border-outline-variant rounded-lg text-sm focus:ring-2 focus:ring-primary focus:border-primary outline-none" min="0" max="100" placeholder="0">
                            <button type="submit" class="px-3 py-1.5 bg-primary text-on-primary rounded-lg text-xs font-bold hover:bg-primary-container transition-all">Simpan</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td class="px-6 py-8 text-center text-on-surface-variant" colspan="6">Belum ada pengumpulan tugas.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
</x-layouts.portal-guru>

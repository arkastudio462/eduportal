<x-layouts.portal-siswa title="Absensi - Portal Siswa">
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
        <h2 class="font-headline-lg text-headline-lg text-primary">Absensi</h2>
        <p class="text-on-surface-variant font-body-md">Lihat riwayat kehadiran Anda</p>
    </div>
</div>

@if($bulanList->isNotEmpty())
<div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-xl border border-outline-variant card-shadow p-4 text-center">
        <div class="text-3xl font-bold text-green-600">{{ $stats['persentase'] }}%</div>
        <div class="text-xs text-on-surface-variant mt-1">Kehadiran</div>
    </div>
    <div class="bg-white rounded-xl border border-outline-variant card-shadow p-4 text-center">
        <div class="text-3xl font-bold text-green-600">{{ $stats['hadir'] }}</div>
        <div class="text-xs text-on-surface-variant mt-1">Hadir</div>
    </div>
    <div class="bg-white rounded-xl border border-outline-variant card-shadow p-4 text-center">
        <div class="text-3xl font-bold text-yellow-600">{{ $stats['sakit'] }}</div>
        <div class="text-xs text-on-surface-variant mt-1">Sakit</div>
    </div>
    <div class="bg-white rounded-xl border border-outline-variant card-shadow p-4 text-center">
        <div class="text-3xl font-bold text-error">{{ $stats['alpha'] }}</div>
        <div class="text-xs text-on-surface-variant mt-1">Alpha</div>
    </div>
</div>

<form method="GET" class="mb-6 flex items-center gap-3">
    <label class="font-label-md text-label-md text-on-surface-variant">Bulan:</label>
    <select name="bulan" onchange="this.form.submit()"
        class="px-4 py-2 border border-outline-variant rounded-lg text-sm focus:ring-2 focus:ring-primary focus:border-primary outline-none bg-white">
        @foreach($bulanList as $b)
        <option value="{{ $b }}" {{ $bulanAktif == $b ? 'selected' : '' }}>
            {{ \Carbon\Carbon::createFromFormat('Y-m', $b)->isoFormat('MMMM YYYY') }}
        </option>
        @endforeach
    </select>
</form>

<div class="bg-white rounded-xl border border-outline-variant card-shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-surface-container-low">
                <tr>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">No</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Tanggal</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Status</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Keterangan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant">
                @forelse($riwayat as $index => $a)
                <tr class="hover:bg-surface-container transition-colors">
                    <td class="px-6 py-4 font-body-md">{{ $index + 1 }}</td>
                    <td class="px-6 py-4 font-body-md">{{ $a->tanggal->isoFormat('D MMMM YYYY') }}</td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 rounded-full text-xs font-bold inline-block
                            {{ $a->status == 'hadir' ? 'bg-green-100 text-green-700' : '' }}
                            {{ $a->status == 'sakit' ? 'bg-yellow-100 text-yellow-700' : '' }}
                            {{ $a->status == 'izin' ? 'bg-blue-100 text-blue-700' : '' }}
                            {{ $a->status == 'alpha' ? 'bg-red-100 text-red-700' : '' }}
                            capitalize">{{ $a->status }}</span>
                    </td>
                    <td class="px-6 py-4 text-on-surface-variant">{{ $a->keterangan ?? '-' }}</td>
                </tr>
                @empty
                <tr><td class="px-6 py-8 text-center text-on-surface-variant" colspan="4">Belum ada data absensi.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@else
<div class="bg-white rounded-xl border border-outline-variant card-shadow p-8 text-center">
    <span class="material-symbols-outlined text-5xl text-outline mb-4">person_check</span>
    <h3 class="font-headline-sm text-headline-sm text-on-surface mb-2">Belum Ada Absensi</h3>
    <p class="text-on-surface-variant">Data absensi belum tersedia.</p>
</div>
@endif
</x-layouts.portal-siswa>

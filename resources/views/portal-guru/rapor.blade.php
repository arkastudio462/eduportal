<x-layouts.portal-guru title="Cetak Rapor - Portal Guru">
<x-slot:styles>
<style>[x-cloak] { display: none !important; }</style>
</x-slot:styles>
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
        <h2 class="font-headline-lg text-headline-lg text-primary">Cetak Rapor</h2>
        <p class="text-on-surface-variant font-body-md">Pilih siswa untuk mencetak laporan hasil belajar</p>
    </div>
</div>

<form method="GET" class="bg-white p-6 rounded-xl border border-outline-variant card-shadow mb-6">
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
            <label class="font-label-sm text-label-sm text-outline mb-1 block">Kelas</label>
            <select name="kelas_id" class="w-full px-3 py-2 border border-outline-variant rounded-lg text-sm focus:ring-2 focus:ring-primary focus:border-primary outline-none bg-white" onchange="this.form.submit()">
                @foreach($kelasList as $kelas)
                <option value="{{ $kelas->id }}" {{ ($selectedKelas->id ?? null) == $kelas->id ? 'selected' : '' }}>{{ $kelas->nama }}</option>
                @endforeach
            </select>
        </div>
    </div>
</form>

@if($siswaList->isNotEmpty())
<div class="bg-white rounded-xl border border-outline-variant card-shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-primary text-on-primary">
                    <th class="text-left py-3 px-4 font-label-md">Nama</th>
                    <th class="text-left py-3 px-4 font-label-md">NISN</th>
                    <th class="text-left py-3 px-4 font-label-md">Kelas</th>
                    <th class="text-center py-3 px-4 font-label-md">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($siswaList as $siswa)
                <tr class="border-t border-outline-variant hover:bg-surface-bright transition-colors">
                    <td class="py-3 px-4 font-body-md">{{ $siswa->user->name }}</td>
                    <td class="py-3 px-4 text-on-surface-variant">{{ $siswa->nisn }}</td>
                    <td class="py-3 px-4">{{ $siswa->kelas?->nama ?? '-' }}</td>
                    <td class="py-3 px-4 text-center">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('portal-guru.rapor.print', $siswa) }}?semester=Ganjil&tahun_ajar={{ now()->year }}"
                               class="inline-flex items-center gap-1 px-3 py-1.5 bg-primary text-on-primary rounded-lg text-xs hover:bg-primary-container transition-colors"
                               target="_blank">
                                <span class="material-symbols-outlined text-[16px]">print</span>
                                Cetak
                            </a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@else
<div class="bg-white rounded-xl border border-outline-variant card-shadow p-8 text-center">
    <span class="material-symbols-outlined text-4xl text-outline mb-2">description</span>
    <p class="font-body-md text-outline">Pilih kelas untuk menampilkan daftar siswa</p>
</div>
@endif
</x-layouts.portal-guru>

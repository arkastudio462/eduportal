<x-layouts.admin title="Anggota {{ $ekskul->nama }}">
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
        <h2 class="font-headline-lg text-headline-lg text-primary">{{ $ekskul->nama }}</h2>
        <p class="text-on-surface-variant font-body-md">Anggota: {{ $anggota->count() }}/{{ $ekskul->kuota }}</p>
    </div>
    <a href="{{ route('admin.ekskul') }}" class="inline-flex items-center gap-2 bg-outline/10 text-outline px-4 py-2 rounded-xl font-label-md hover:bg-primary hover:text-on-primary transition-colors">
        <span class="material-symbols-outlined">arrow_back</span>
        Kembali
    </a>
</div>

@if($siswaList->isNotEmpty())
<div class="bg-white rounded-xl border border-outline-variant card-shadow p-4 mb-6">
    <form method="POST" action="{{ route('admin.ekskul.anggota.add', $ekskul) }}" class="flex items-center gap-3">
        @csrf
        <select name="siswa_id" required class="flex-1 px-3 py-2 border border-outline-variant rounded-lg text-sm focus:ring-2 focus:ring-primary focus:border-primary outline-none bg-white">
            <option value="">Pilih Siswa</option>
            @foreach($siswaList as $siswa)
            <option value="{{ $siswa->id }}">{{ $siswa->user->name }} ({{ $siswa->nisn }})</option>
            @endforeach
        </select>
        <button type="submit" class="bg-primary text-on-primary px-4 py-2 rounded-xl font-label-md text-sm hover:bg-primary-container transition-colors">Tambah Anggota</button>
    </form>
</div>
@endif

<div class="bg-white rounded-xl border border-outline-variant card-shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-primary text-on-primary">
                    <th class="text-left py-3 px-4 font-label-md">Nama</th>
                    <th class="text-left py-3 px-4 font-label-md">NISN</th>
                    <th class="text-left py-3 px-4 font-label-md">Status</th>
                    <th class="text-center py-3 px-4 font-label-md">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($anggota as $siswa)
                <tr class="border-t border-outline-variant hover:bg-surface-bright transition-colors">
                    <td class="py-3 px-4 font-body-md">{{ $siswa->user->name }}</td>
                    <td class="py-3 px-4">{{ $siswa->nisn }}</td>
                    <td class="py-3 px-4">{{ $siswa->pivot->status }}</td>
                    <td class="py-3 px-4 text-center">
                        <form method="POST" action="{{ route('admin.ekskul.anggota.remove', [$ekskul, $siswa]) }}" x-data @submit.prevent="Swal.fire({title:'Konfirmasi',text:'Hapus anggota ini?',icon:'warning',showCancelButton:true,confirmButtonText:'Ya, hapus!',cancelButtonText:'Batal'}).then(r=>r.isConfirmed&&$el.submit())">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-error hover:text-red-700"><span class="material-symbols-outlined text-lg">delete</span></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="py-8 text-center text-outline">Belum ada anggota</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
</x-layouts.admin>

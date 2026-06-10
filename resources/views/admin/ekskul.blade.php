<x-layouts.admin title="Ekstrakurikuler">
<div x-data="{ showModal: false }">
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
        <h2 class="font-headline-lg text-headline-lg text-primary">Ekstrakurikuler</h2>
        <p class="text-on-surface-variant font-body-md">Kelola kegiatan ekstrakurikuler sekolah</p>
    </div>
    <button @click="showModal = true" class="inline-flex items-center gap-2 bg-primary text-on-primary px-4 py-2 rounded-xl font-label-md hover:bg-primary-container transition-colors">
        <span class="material-symbols-outlined">add</span>
        Tambah Ekskul
    </button>
</div>

<div>
    <div x-show="showModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/40" @click.self="showModal = false">
        <div class="bg-white rounded-2xl p-6 w-full max-w-lg mx-4 shadow-xl">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-headline-md text-headline-md text-primary">Tambah Ekstrakurikuler</h3>
                <button @click="showModal = false" class="text-outline hover:text-on-surface"><span class="material-symbols-outlined">close</span></button>
            </div>
            <form method="POST" action="{{ route('admin.ekskul.store') }}" class="space-y-4">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-2"><label class="font-label-sm text-label-sm text-outline mb-1 block">Nama Ekskul</label><input type="text" name="nama" required class="w-full px-3 py-2 border border-outline-variant rounded-lg text-sm focus:ring-2 focus:ring-primary focus:border-primary outline-none"></div>
                    <div class="col-span-2"><label class="font-label-sm text-label-sm text-outline mb-1 block">Pembina</label><input type="text" name="pembina" required class="w-full px-3 py-2 border border-outline-variant rounded-lg text-sm focus:ring-2 focus:ring-primary focus:border-primary outline-none"></div>
                    <div><label class="font-label-sm text-label-sm text-outline mb-1 block">Hari</label>
                        <select name="hari" class="w-full px-3 py-2 border border-outline-variant rounded-lg text-sm focus:ring-2 focus:ring-primary focus:border-primary outline-none bg-white">
                            @foreach(['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'] as $h)
                            <option value="{{ $h }}">{{ $h }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div><label class="font-label-sm text-label-sm text-outline mb-1 block">Kuota</label><input type="number" name="kuota" required min="1" class="w-full px-3 py-2 border border-outline-variant rounded-lg text-sm focus:ring-2 focus:ring-primary focus:border-primary outline-none"></div>
                    <div><label class="font-label-sm text-label-sm text-outline mb-1 block">Jam Mulai</label><input type="time" name="jam_mulai" required class="w-full px-3 py-2 border border-outline-variant rounded-lg text-sm focus:ring-2 focus:ring-primary focus:border-primary outline-none"></div>
                    <div><label class="font-label-sm text-label-sm text-outline mb-1 block">Jam Selesai</label><input type="time" name="jam_selesai" required class="w-full px-3 py-2 border border-outline-variant rounded-lg text-sm focus:ring-2 focus:ring-primary focus:border-primary outline-none"></div>
                    <div class="col-span-2"><label class="font-label-sm text-label-sm text-outline mb-1 block">Tempat</label><input type="text" name="tempat" required class="w-full px-3 py-2 border border-outline-variant rounded-lg text-sm focus:ring-2 focus:ring-primary focus:border-primary outline-none"></div>
                    <div class="col-span-2"><label class="font-label-sm text-label-sm text-outline mb-1 block">Deskripsi</label><textarea name="deskripsi" rows="3" class="w-full px-3 py-2 border border-outline-variant rounded-lg text-sm focus:ring-2 focus:ring-primary focus:border-primary outline-none"></textarea></div>
                </div>
                <button type="submit" class="w-full bg-primary text-on-primary py-2.5 rounded-xl font-label-md hover:bg-primary-container transition-colors">Simpan</button>
            </form>
        </div>
    </div>
</div>

<div class="bg-white rounded-xl border border-outline-variant card-shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-primary text-on-primary">
                    <th class="text-left py-3 px-4 font-label-md">Nama</th>
                    <th class="text-left py-3 px-4 font-label-md">Pembina</th>
                    <th class="text-left py-3 px-4 font-label-md">Jadwal</th>
                    <th class="text-left py-3 px-4 font-label-md">Tempat</th>
                    <th class="text-center py-3 px-4 font-label-md">Anggota</th>
                    <th class="text-center py-3 px-4 font-label-md">Kuota</th>
                    <th class="text-center py-3 px-4 font-label-md">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($ekskuls as $ekskul)
                <tr class="border-t border-outline-variant hover:bg-surface-bright transition-colors">
                    <td class="py-3 px-4 font-body-md">{{ $ekskul->nama }}</td>
                    <td class="py-3 px-4">{{ $ekskul->pembina }}</td>
                    <td class="py-3 px-4 text-on-surface-variant">{{ $ekskul->hari }}, {{ substr($ekskul->jam_mulai, 0, 5) }}-{{ substr($ekskul->jam_selesai, 0, 5) }}</td>
                    <td class="py-3 px-4">{{ $ekskul->tempat }}</td>
                    <td class="py-3 px-4 text-center">{{ $ekskul->anggota_count }}</td>
                    <td class="py-3 px-4 text-center">{{ $ekskul->kuota }}</td>
                    <td class="py-3 px-4 text-center">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('admin.ekskul.anggota', $ekskul) }}" class="inline-flex items-center gap-1 px-3 py-1.5 bg-secondary text-on-secondary rounded-lg text-xs hover:bg-secondary-container transition-colors">
                                <span class="material-symbols-outlined text-[16px]">group</span>
                                Anggota
                            </a>
                            <form method="POST" action="{{ route('admin.ekskul.destroy', $ekskul) }}" @submit.prevent="Swal.fire({title:'Konfirmasi',text:'Hapus ekskul ini?',icon:'warning',showCancelButton:true,confirmButtonText:'Ya, hapus!',cancelButtonText:'Batal'}).then(r=>r.isConfirmed&&$el.submit())">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-error hover:text-red-700"><span class="material-symbols-outlined text-lg">delete</span></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="py-8 text-center text-outline">Belum ada data ekskul</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
</div>
</x-layouts.admin>

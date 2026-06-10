<x-layouts.portal-guru title="Modul Bahan Ajar - Portal Guru">
<x-slot:styles>
<style>[x-cloak] { display: none !important; }</style>
</x-slot:styles>
<div x-data="{ showUpload: false }">
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
        <h2 class="font-headline-lg text-headline-lg text-primary">Modul Bahan Ajar</h2>
        <p class="text-on-surface-variant font-body-md">Unggah dan kelola modul pembelajaran</p>
    </div>
    <button @click="showUpload = true" class="inline-flex items-center gap-2 bg-primary text-on-primary px-4 py-2 rounded-xl font-label-md hover:bg-primary-container transition-colors">
        <span class="material-symbols-outlined">upload_file</span>
        Unggah Modul
    </button>
</div>
    <div x-show="showUpload" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/40" @click.self="showUpload = false">
        <div class="bg-white rounded-2xl p-6 w-full max-w-lg mx-4 shadow-xl">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-headline-md text-headline-md text-primary">Unggah Modul Baru</h3>
                <button @click="showUpload = false" class="text-outline hover:text-on-surface">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <form method="POST" action="{{ route('portal-guru.modul.store') }}" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label class="font-label-sm text-label-sm text-outline mb-1 block">Judul Modul</label>
                    <input type="text" name="judul" required class="w-full px-3 py-2 border border-outline-variant rounded-lg text-sm focus:ring-2 focus:ring-primary focus:border-primary outline-none">
                </div>
                <div>
                    <label class="font-label-sm text-label-sm text-outline mb-1 block">Deskripsi</label>
                    <textarea name="deskripsi" rows="3" class="w-full px-3 py-2 border border-outline-variant rounded-lg text-sm focus:ring-2 focus:ring-primary focus:border-primary outline-none"></textarea>
                </div>
                <div>
                    <label class="font-label-sm text-label-sm text-outline mb-1 block">Kelas (opsional)</label>
                    <select name="kelas_id" class="w-full px-3 py-2 border border-outline-variant rounded-lg text-sm focus:ring-2 focus:ring-primary focus:border-primary outline-none bg-white">
                        <option value="">Semua Kelas</option>
                        @foreach($kelasList as $kelas)
                        <option value="{{ $kelas->id }}">{{ $kelas->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="font-label-sm text-label-sm text-outline mb-1 block">File (PDF, DOC, PPT, ZIP - maks 20MB)</label>
                    <input type="file" name="file" required accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.zip,.rar" class="w-full px-3 py-2 border border-outline-variant rounded-lg text-sm focus:ring-2 focus:ring-primary focus:border-primary outline-none">
                </div>
                <button type="submit" class="w-full bg-primary text-on-primary py-2.5 rounded-xl font-label-md hover:bg-primary-container transition-colors">Unggah Modul</button>
            </form>
        </div>
    </div>
</div>

<div class="bg-white rounded-xl border border-outline-variant card-shadow overflow-hidden">
    @if($moduls->isEmpty())
    <div class="p-8 text-center">
        <span class="material-symbols-outlined text-4xl text-outline mb-2">folder</span>
        <p class="font-body-md text-outline">Belum ada modul yang diunggah</p>
    </div>
    @else
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-primary text-on-primary">
                    <th class="text-left py-3 px-4 font-label-md">Judul</th>
                    <th class="text-left py-3 px-4 font-label-md">Mapel</th>
                    <th class="text-left py-3 px-4 font-label-md">Kelas</th>
                    <th class="text-left py-3 px-4 font-label-md">File</th>
                    <th class="text-left py-3 px-4 font-label-md">Tanggal</th>
                    <th class="text-center py-3 px-4 font-label-md">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($moduls as $modul)
                <tr class="border-t border-outline-variant hover:bg-surface-bright transition-colors">
                    <td class="py-3 px-4 font-body-md">{{ $modul->judul }}</td>
                    <td class="py-3 px-4">{{ $modul->mapel?->nama ?? '-' }}</td>
                    <td class="py-3 px-4">{{ $modul->kelas?->nama ?? 'Semua' }}</td>
                    <td class="py-3 px-4 text-on-surface-variant">{{ strtoupper($modul->ekstensi) }} - {{ round($modul->ukuran / 1024) }}KB</td>
                    <td class="py-3 px-4 text-on-surface-variant">{{ $modul->created_at->format('d/m/Y') }}</td>
                    <td class="py-3 px-4 text-center">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('portal-guru.modul.download', $modul) }}" class="inline-flex items-center gap-1 px-3 py-1.5 bg-primary text-on-primary rounded-lg text-xs hover:bg-primary-container transition-colors">
                                <span class="material-symbols-outlined text-[16px]">download</span>
                                Download
                            </a>
                            <form method="POST" action="{{ route('portal-guru.modul.destroy', $modul) }}" x-data @submit.prevent="Swal.fire({title:'Konfirmasi',text:'Hapus modul ini?',icon:'warning',showCancelButton:true,confirmButtonText:'Ya, hapus!',cancelButtonText:'Batal'}).then(r=>r.isConfirmed&&$el.submit())">
                                @csrf @method('DELETE')
                                <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 bg-error text-on-error rounded-lg text-xs hover:bg-red-700 transition-colors">
                                    <span class="material-symbols-outlined text-[16px]">delete</span>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>
</x-layouts.portal-guru>

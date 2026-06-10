<x-layouts.portal-guru title="Ujian Online - Portal Guru">
<div x-data="{ openCreate: false, openEdit: false, editId: null, form: { nama: '', tanggal_mulai: '', tanggal_selesai: '', durasi: 90, status: 'draft', kelas_ids: [] } }">
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
        <h2 class="font-headline-lg text-headline-lg text-primary">Ujian Online</h2>
        <p class="text-on-surface-variant font-body-md">Buat dan kelola ujian online untuk {{ $mapel?->nama ?? 'mata pelajaran Anda' }}</p>
    </div>
    @if($mapel)
    <button @click="openCreate = true" class="px-4 py-2.5 bg-primary text-on-primary rounded-lg flex items-center gap-2 font-label-md hover:bg-primary/90 transition-all active:scale-95">
        <span class="material-symbols-outlined">add</span>
        Buat Ujian
    </button>
    @endif
</div>

@if($mapel && $ujianList->isNotEmpty())
<div class="grid grid-cols-1 gap-gutter">
    @foreach($ujianList as $ujian)
    <div class="bg-white rounded-xl border border-outline-variant card-shadow overflow-hidden hover:translate-y-[-2px] transition-all duration-300">
        <div class="p-6">
            <div class="flex items-start justify-between gap-4">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-2">
                        <h3 class="font-headline-sm text-headline-sm text-primary">{{ $ujian->nama }}</h3>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold
                            {{ $ujian->status == 'selesai' ? 'bg-surface-container text-outline' : '' }}
                            {{ $ujian->status == 'sedang_berlangsung' ? 'bg-secondary-container text-secondary' : '' }}
                            {{ $ujian->status == 'akan_datang' ? 'bg-primary-fixed text-primary' : '' }}
                            {{ $ujian->status == 'draft' ? 'bg-error-container text-error' : '' }}">
                            {{ str_replace('_', ' ', ucfirst($ujian->status)) }}
                        </span>
                    </div>
                    <div class="flex flex-wrap items-center gap-x-6 gap-y-2 text-sm text-on-surface-variant">
                        <span class="flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[16px]">book</span>
                            {{ $ujian->mapel->nama ?? '-' }}
                        </span>
                        <span class="flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[16px]">timer</span>
                            {{ $ujian->durasi }} menit
                        </span>
                        <span class="flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[16px]">calendar_month</span>
                            {{ $ujian->tanggal_mulai->isoFormat('DD MMM YYYY') }}
                            @if($ujian->tanggal_selesai && $ujian->tanggal_selesai != $ujian->tanggal_mulai)
                            - {{ $ujian->tanggal_selesai->isoFormat('DD MMM YYYY') }}
                            @endif
                        </span>
                        <span class="flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[16px]">group</span>
                            {{ $ujian->kelas->pluck('nama')->implode(', ') }}
                        </span>
                    </div>
                </div>
                <div class="text-right shrink-0">
                    <p class="text-2xl font-bold text-primary">{{ $ujian->jumlah_peserta }}</p>
                    <p class="text-xs text-on-surface-variant">Peserta</p>
                </div>
            </div>
            <div class="flex items-center gap-2 mt-4 pt-4 border-t border-outline-variant">
                <button @click="editUjian({{ $ujian->id }})" class="px-3 py-1.5 border border-outline-variant rounded-lg text-sm text-outline hover:bg-surface-container transition-all flex items-center gap-1">
                    <span class="material-symbols-outlined text-[16px]">edit</span> Edit
                </button>
                <form method="POST" action="{{ route('portal-guru.ujian.destroy', $ujian) }}" @submit.prevent="Swal.fire({title:'Konfirmasi',text:'Hapus ujian ini?',icon:'warning',showCancelButton:true,confirmButtonText:'Ya, hapus!',cancelButtonText:'Batal'}).then(r=>r.isConfirmed&&$el.submit())">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-3 py-1.5 border border-outline-variant rounded-lg text-sm text-error hover:bg-error-container transition-all flex items-center gap-1">
                        <span class="material-symbols-outlined text-[16px]">delete</span> Hapus
                    </button>
                </form>
            </div>
        </div>
        @if($ujian->jumlah_peserta > 0)
        <div class="px-6 py-3 bg-surface-container-low border-t border-outline-variant flex items-center justify-between">
            <span class="text-sm text-on-surface-variant">Lihat hasil peserta</span>
            <a href="#" class="text-primary text-sm font-bold hover:underline flex items-center gap-1">
                Detail
                <span class="material-symbols-outlined text-[16px]">chevron_right</span>
            </a>
        </div>
        @endif
    </div>
    @endforeach
</div>
@else
<div class="bg-white rounded-xl border border-outline-variant card-shadow p-12 text-center">
    <span class="material-symbols-outlined text-6xl text-outline mb-4">quiz</span>
    <h3 class="font-headline-sm text-headline-sm text-on-surface mb-2">Belum Ada Ujian</h3>
    <p class="text-on-surface-variant">Belum ada ujian yang tersedia untuk mata pelajaran Anda.</p>
</div>
@endif

@if($mapel)
<!-- Create Exam Modal -->
<div x-show="openCreate" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" x-effect="if (openCreate) { $nextTick(() => { document.body.style.overflow = 'hidden'; }) } else { document.body.style.overflow = '' }">
    <div class="fixed inset-0 bg-black/40" @click="openCreate = false"></div>
    <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-lg max-h-[90vh] overflow-y-auto z-10 p-6">
        <form method="POST" action="{{ route('portal-guru.ujian.store') }}">
            @csrf
            <div class="flex justify-between items-center mb-6">
                <h3 class="font-headline-md text-headline-md text-primary">Buat Ujian Baru</h3>
                <button type="button" @click="openCreate = false" class="p-2 hover:bg-surface-container rounded-full"><span class="material-symbols-outlined">close</span></button>
            </div>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-on-surface-variant mb-1">Nama Ujian</label>
                    <input type="text" name="nama" x-model="form.nama" class="w-full px-3 py-2 border border-outline-variant rounded-lg text-sm focus:ring-2 focus:ring-primary focus:border-primary outline-none" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-on-surface-variant mb-1">Mata Pelajaran</label>
                    <input type="text" value="{{ $mapel->nama }}" readonly class="w-full px-3 py-2 border border-outline-variant rounded-lg text-sm bg-surface-container-low outline-none">
                    <input type="hidden" name="mapel_id" value="{{ $mapel->id }}">
                </div>
                <div>
                    <label class="block text-sm font-medium text-on-surface-variant mb-1">Kelas</label>
                    <div class="grid grid-cols-2 gap-2 max-h-40 overflow-y-auto p-2 border border-outline-variant rounded-lg">
                        @foreach($kelasList as $kelas)
                        <label class="flex items-center gap-2 text-sm">
                            <input type="checkbox" name="kelas_ids[]" value="{{ $kelas->id }}" x-model="form.kelas_ids">
                            {{ $kelas->nama }}
                        </label>
                        @endforeach
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-on-surface-variant mb-1">Tanggal Mulai</label>
                        <input type="date" name="tanggal_mulai" x-model="form.tanggal_mulai" class="w-full px-3 py-2 border border-outline-variant rounded-lg text-sm focus:ring-2 focus:ring-primary focus:border-primary outline-none" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-on-surface-variant mb-1">Tanggal Selesai</label>
                        <input type="date" name="tanggal_selesai" x-model="form.tanggal_selesai" class="w-full px-3 py-2 border border-outline-variant rounded-lg text-sm focus:ring-2 focus:ring-primary focus:border-primary outline-none" required>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-on-surface-variant mb-1">Durasi (menit)</label>
                        <input type="number" name="durasi" x-model="form.durasi" min="1" class="w-full px-3 py-2 border border-outline-variant rounded-lg text-sm focus:ring-2 focus:ring-primary focus:border-primary outline-none" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-on-surface-variant mb-1">Status</label>
                        <select name="status" x-model="form.status" class="w-full px-3 py-2 border border-outline-variant rounded-lg text-sm bg-white focus:ring-2 focus:ring-primary focus:border-primary outline-none" required>
                            <option value="draft">Draft</option>
                            <option value="akan_datang">Akan Datang</option>
                            <option value="sedang_berlangsung">Sedang Berlangsung</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-outline-variant">
                <button type="button" @click="openCreate = false" class="px-6 py-2.5 border border-outline-variant rounded-lg font-label-md text-outline hover:bg-surface-container transition-all">Batal</button>
                <button type="submit" class="px-6 py-2.5 bg-primary text-on-primary rounded-lg font-label-md hover:bg-primary/90 transition-all active:scale-95">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endif
</div>
</x-layouts.portal-guru>

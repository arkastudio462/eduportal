<x-layouts.admin title="Ujian Online | EduPortal">
<div x-data="ujianApp()">
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
        <h2 class="font-headline-lg text-headline-lg text-primary">Ujian Online</h2>
        <p class="text-on-surface-variant font-body-md">Kelola jadwal & pelaksanaan ujian</p>
    </div>
    <a href="{{ route('admin.ujian-online') }}?tambah=1" class="px-4 py-2.5 bg-primary text-on-primary rounded-lg flex items-center gap-2 font-label-md hover:bg-primary-container transition-all active:scale-95">
        <span class="material-symbols-outlined">add</span>
        Buat Ujian Baru
    </a>
</div>

<!-- Summary -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-gutter mb-6">
    @php $stats = [['Total Ujian', $totalUjian, 'assignment', 'bg-primary-fixed text-primary'], ['Sedang Berlangsung', $sedangBerlangsung, 'play_circle', 'bg-secondary-fixed text-secondary'], ['Akan Datang', $akanDatang, 'schedule', 'bg-tertiary-fixed text-on-tertiary-container'], ['Selesai', $selesai, 'check_circle', 'bg-green-100 text-green-700']]; @endphp
    @foreach($stats as $stat)
    <div class="bg-white p-5 rounded-xl border border-outline-variant card-shadow hover:translate-y-[-4px] transition-transform">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl {{ $stat[3] }} flex items-center justify-center">
                <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">{{ $stat[2] }}</span>
            </div>
            <div>
                <p class="text-on-surface-variant font-label-md">{{ $stat[0] }}</p>
                <h3 class="font-headline-md text-headline-md text-primary">{{ $stat[1] }}</h3>
            </div>
        </div>
    </div>
    @endforeach
</div>

<!-- Table -->
<div class="bg-white rounded-xl border border-outline-variant card-shadow overflow-hidden">
    <div class="p-4 md:p-6 border-b border-outline-variant flex flex-col sm:flex-row items-center justify-between gap-4">
        <form method="GET" action="{{ route('admin.ujian-online') }}" class="flex flex-col sm:flex-row items-center gap-3 w-full">
            <div class="flex items-center gap-3">
                <span class="material-symbols-outlined text-outline">filter_alt</span>
                <select name="status" class="border border-outline-variant rounded-lg px-3 py-2 text-sm bg-white focus:ring-2 focus:ring-primary focus:border-primary outline-none">
                    <option value="">Semua Status</option>
                    <option value="sedang_berlangsung" {{ request('status') == 'sedang_berlangsung' ? 'selected' : '' }}>Sedang Berlangsung</option>
                    <option value="akan_datang" {{ request('status') == 'akan_datang' ? 'selected' : '' }}>Akan Datang</option>
                    <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                </select>
                <select name="mapel" class="border border-outline-variant rounded-lg px-3 py-2 text-sm bg-white focus:ring-2 focus:ring-primary focus:border-primary outline-none">
                    <option value="">Semua Mata Pelajaran</option>
                    @foreach($daftarMapel as $mapel)
                    <option value="{{ $mapel->id }}" {{ request('mapel') == $mapel->id ? 'selected' : '' }}>{{ $mapel->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="relative w-full sm:w-auto">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline text-sm">search</span>
                <input name="search" value="{{ request('search') }}" class="w-full sm:w-64 pl-10 pr-3 py-2 border border-outline-variant rounded-lg text-sm focus:ring-2 focus:ring-primary focus:border-primary outline-none" placeholder="Cari ujian..." type="text">
            </div>
        </form>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-surface-container-low">
                <tr>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Nama Ujian</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Kelas</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Tanggal</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Durasi</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Peserta</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Status</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant">
                @forelse($semuaUjian as $ujian)
                @php
                    $statusMap = ['sedang_berlangsung' => ['bg-yellow-100 text-yellow-700', 'Sedang Berlangsung'], 'akan_datang' => ['bg-blue-100 text-blue-700', 'Akan Datang'], 'selesai' => ['bg-green-100 text-green-700', 'Selesai'], 'draft' => ['bg-purple-100 text-purple-700', 'Draft']];
                    $status = $statusMap[$ujian->status] ?? ['bg-surface-container text-outline', $ujian->status];
                @endphp
                <tr class="hover:bg-surface-container transition-colors">
                    <td class="px-6 py-4 font-body-md font-semibold">{{ $ujian->nama }}</td>
                    <td class="px-6 py-4 font-body-md">{{ $ujian->kelas->pluck('nama')->implode(', ') }}</td>
                    <td class="px-6 py-4 font-body-md text-on-surface-variant">{{ $ujian->tanggal_mulai->format('d M') }} - {{ $ujian->tanggal_selesai->format('d M Y') }}</td>
                    <td class="px-6 py-4 font-body-md">{{ $ujian->durasi }} Menit</td>
                    <td class="px-6 py-4 font-body-md text-center">{{ $ujian->nilai_count ?? '-' }}</td>
                    <td class="px-6 py-4"><span class="px-3 py-1 rounded-full text-xs font-bold uppercase {{ $status[0] }}">{{ $status[1] }}</span></td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-1">
                            <button @click="editUjian({{ $ujian->id }})" class="p-1.5 hover:bg-surface-container rounded" title="Edit"><span class="material-symbols-outlined text-outline text-sm">edit</span></button>
                            <button @click="previewUjian({{ $ujian->id }})" class="p-1.5 hover:bg-surface-container rounded" title="Detail"><span class="material-symbols-outlined text-outline text-sm">visibility</span></button>
                            <button @click="confirmDelete({{ $ujian->id }})" class="p-1.5 hover:bg-error-container rounded" title="Hapus"><span class="material-symbols-outlined text-outline text-sm">delete</span></button>
                            <form method="POST" action="{{ route('admin.ujian-online.destroy', $ujian->id) }}" id="delete-form-{{ $ujian->id }}" class="hidden">
                                @csrf
                                @method('DELETE')
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td class="px-6 py-4 text-on-surface-variant" colspan="7">Tidak ada ujian.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-outline-variant">
        {{ $semuaUjian->links() }}
    </div>

    <!-- Create/Edit Modal -->
    <div x-show="openModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" x-effect="if (openModal) { $nextTick(() => { document.body.style.overflow = 'hidden'; }) } else { document.body.style.overflow = '' }">
        <div class="fixed inset-0 bg-black/40" @click="openModal = false"></div>
        <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto z-10 p-6">
            <form method="POST" :action="editId ? '{{ route('admin.ujian-online.update', '__ID__') }}'.replace('__ID__', editId) : '{{ route('admin.ujian-online.store') }}'">
                @csrf
                <input type="hidden" name="_method" :value="editId ? 'PUT' : 'POST'">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="font-headline-md text-headline-md text-primary" x-text="editId ? 'Edit Ujian' : 'Buat Ujian Baru'"></h3>
                    <button type="button" @click="openModal = false" class="p-2 hover:bg-surface-container rounded-full"><span class="material-symbols-outlined">close</span></button>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-on-surface-variant mb-1">Nama Ujian</label>
                        <input type="text" name="nama" x-model="form.nama" class="w-full px-3 py-2 border border-outline-variant rounded-lg text-sm focus:ring-2 focus:ring-primary focus:border-primary outline-none" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-on-surface-variant mb-1">Mata Pelajaran</label>
                        <select name="mapel_id" x-model="form.mapel_id" class="w-full px-3 py-2 border border-outline-variant rounded-lg text-sm bg-white focus:ring-2 focus:ring-primary focus:border-primary outline-none" required>
                            <option value="">Pilih Mapel</option>
                            @foreach($daftarMapel as $mapel)
                            <option value="{{ $mapel->id }}">{{ $mapel->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-on-surface-variant mb-1">Durasi (Menit)</label>
                        <input type="number" name="durasi" x-model="form.durasi" class="w-full px-3 py-2 border border-outline-variant rounded-lg text-sm focus:ring-2 focus:ring-primary focus:border-primary outline-none" min="1" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-on-surface-variant mb-1">Tanggal Mulai</label>
                        <input type="date" name="tanggal_mulai" x-model="form.tanggal_mulai" class="w-full px-3 py-2 border border-outline-variant rounded-lg text-sm focus:ring-2 focus:ring-primary focus:border-primary outline-none" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-on-surface-variant mb-1">Tanggal Selesai</label>
                        <input type="date" name="tanggal_selesai" x-model="form.tanggal_selesai" class="w-full px-3 py-2 border border-outline-variant rounded-lg text-sm focus:ring-2 focus:ring-primary focus:border-primary outline-none" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-on-surface-variant mb-1">Status</label>
                        <select name="status" x-model="form.status" class="w-full px-3 py-2 border border-outline-variant rounded-lg text-sm bg-white focus:ring-2 focus:ring-primary focus:border-primary outline-none" required>
                            <option value="draft">Draft</option>
                            <option value="akan_datang">Akan Datang</option>
                            <option value="sedang_berlangsung">Sedang Berlangsung</option>
                            <option value="selesai">Selesai</option>
                        </select>
                    </div>
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-medium text-on-surface-variant mb-2">Kelas Peserta</label>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                        @foreach($daftarKelas as $kelas)
                        <label class="flex items-center gap-2 p-2 rounded-lg border border-outline-variant hover:bg-surface-container cursor-pointer text-sm">
                            <input type="checkbox" name="kelas_ids[]" value="{{ $kelas->id }}" x-model="form.kelas_ids" :checked="form.kelas_ids.includes('{{ $kelas->id }}')">
                            {{ $kelas->nama }}
                        </label>
                        @endforeach
                    </div>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" @click="openModal = false" class="px-6 py-2.5 border border-outline-variant rounded-lg font-label-md text-outline hover:bg-surface-container transition-all">Batal</button>
                    <button type="submit" class="px-6 py-2.5 bg-primary text-on-primary rounded-lg font-label-md hover:bg-primary-container transition-all active:scale-95" x-text="editId ? 'Simpan Perubahan' : 'Simpan'"></button>
                </div>
            </form>
        </div>
    </div>

    <!-- Detail Modal -->
    <div x-show="openDetail" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-black/40" @click="openDetail = false"></div>
        <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto z-10 p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="font-headline-md text-headline-md text-primary">Detail Ujian</h3>
                <button type="button" @click="openDetail = false" class="p-2 hover:bg-surface-container rounded-full"><span class="material-symbols-outlined">close</span></button>
            </div>
            <template x-if="detailData">
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <span class="text-xs text-on-surface-variant">Nama Ujian</span>
                            <p class="font-medium" x-text="detailData.nama"></p>
                        </div>
                        <div>
                            <span class="text-xs text-on-surface-variant">Mata Pelajaran</span>
                            <p class="font-medium" x-text="detailData.mapel?.nama"></p>
                        </div>
                        <div>
                            <span class="text-xs text-on-surface-variant">Tanggal Mulai</span>
                            <p class="font-medium" x-text="detailData.tanggal_mulai"></p>
                        </div>
                        <div>
                            <span class="text-xs text-on-surface-variant">Tanggal Selesai</span>
                            <p class="font-medium" x-text="detailData.tanggal_selesai"></p>
                        </div>
                        <div>
                            <span class="text-xs text-on-surface-variant">Durasi</span>
                            <p class="font-medium" x-text="detailData.durasi + ' Menit'"></p>
                        </div>
                        <div>
                            <span class="text-xs text-on-surface-variant">Status</span>
                            <p class="font-medium" x-text="detailData.status?.replace('_', ' ')" :class="detailData.status === 'sedang_berlangsung' ? 'text-yellow-600' : (detailData.status === 'akan_datang' ? 'text-blue-600' : (detailData.status === 'selesai' ? 'text-green-600' : 'text-purple-600'))"></p>
                        </div>
                    </div>
                    <div>
                        <span class="text-xs text-on-surface-variant">Kelas Peserta</span>
                        <div class="flex flex-wrap gap-2 mt-1">
                            <template x-for="k in detailData.kelas" :key="k.id">
                                <span class="px-2 py-0.5 rounded text-xs font-bold bg-primary-fixed text-primary" x-text="k.nama"></span>
                            </template>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>

</div><!-- end table card -->
</div><!-- end x-data -->

<x-slot:scripts>
<script>
function ujianApp() {
    return {
        openModal: {{ request('tambah') ? 'true' : 'false' }},
        openDetail: false,
        editId: null,
        detailData: null,
        form: {
            nama: '',
            mapel_id: '',
            durasi: 60,
            tanggal_mulai: '',
            tanggal_selesai: '',
            status: 'draft',
            kelas_ids: [],
        },
        resetForm() {
            this.editId = null;
            this.form = { nama: '', mapel_id: '', durasi: 60, tanggal_mulai: '', tanggal_selesai: '', status: 'draft', kelas_ids: [] };
        },
        editUjian(id) {
            fetch('{{ route('admin.ujian-online.show', '__ID__') }}'.replace('__ID__', id))
                .then(r => r.json())
                .then(data => {
                    this.editId = data.id;
                    this.form.nama = data.nama;
                    this.form.mapel_id = data.mapel_id;
                    this.form.durasi = data.durasi;
                    this.form.tanggal_mulai = data.tanggal_mulai;
                    this.form.tanggal_selesai = data.tanggal_selesai;
                    this.form.status = data.status;
                    this.form.kelas_ids = data.kelas.map(k => String(k.id));
                    this.openModal = true;
                });
        },
        previewUjian(id) {
            fetch('{{ route('admin.ujian-online.show', '__ID__') }}'.replace('__ID__', id))
                .then(r => r.json())
                .then(data => {
                    this.detailData = data;
                    this.openDetail = true;
                });
        },
        confirmDelete(id) {
            Swal.fire({
                title: 'Hapus Ujian?',
                text: 'Data yang dihapus tidak dapat dikembalikan.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }
    }
}
</script>
</x-slot:scripts>
</x-layouts.admin>
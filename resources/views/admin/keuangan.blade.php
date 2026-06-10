<x-layouts.admin title="Keuangan | EduPortal">
<x-slot:styles>
<style>[x-cloak] { display: none !important; }</style>
</x-slot:styles>
<div x-data="{ openCreate: false, openEdit: false, editId: null, form: { siswa_id: '{{ $siswaList->first()->id ?? '' }}', bulan: '{{ $bulanAktif }}', tahun: '{{ $tahunAktif }}', jumlah: '', status: 'lunas', tanggal_bayar: '' } }">
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
        <h2 class="font-headline-lg text-headline-lg text-primary">Keuangan</h2>
        <p class="text-on-surface-variant font-body-md">Kelola pembayaran SPP dan transaksi keuangan</p>
    </div>
    <button @click="openCreate = true; form = { siswa_id: '{{ $siswaList->first()->id ?? '' }}', bulan: '{{ $bulanAktif }}', tahun: '{{ $tahunAktif }}', jumlah: '100000', status: 'lunas', tanggal_bayar: '{{ date('Y-m-d') }}' }" class="px-4 py-2.5 bg-primary text-on-primary rounded-lg flex items-center gap-2 font-label-md hover:bg-primary/90 transition-all active:scale-95">
        <span class="material-symbols-outlined">add</span>
        Catat Pembayaran
    </button>
</div>

<!-- Stats -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-xl border border-outline-variant card-shadow p-4">
        <div class="text-xs text-on-surface-variant">Total Terkumpul</div>
        <div class="text-2xl font-bold text-green-600 mt-1">Rp {{ number_format($totalSudah, 0, ',', '.') }}</div>
    </div>
    <div class="bg-white rounded-xl border border-outline-variant card-shadow p-4">
        <div class="text-xs text-on-surface-variant">Tunggakan</div>
        <div class="text-2xl font-bold text-error mt-1">Rp {{ number_format($totalTunggakan, 0, ',', '.') }}</div>
    </div>
    <div class="bg-white rounded-xl border border-outline-variant card-shadow p-4">
        <div class="text-xs text-on-surface-variant">Lunas / Total</div>
        <div class="text-2xl font-bold text-primary mt-1">{{ $totalLunas }}/{{ $totalPembayaran }}</div>
    </div>
    <div class="bg-white rounded-xl border border-outline-variant card-shadow p-4">
        <div class="text-xs text-on-surface-variant">Persentase</div>
        <div class="text-2xl font-bold text-amber-600 mt-1">{{ $persentase }}%</div>
    </div>
</div>

<!-- Filter -->
<form method="GET" class="bg-white p-6 rounded-xl border border-outline-variant card-shadow mb-6">
    <div class="grid grid-cols-1 sm:grid-cols-5 gap-4">
        <div>
            <label class="font-label-sm text-label-sm text-outline block mb-1">Kelas</label>
            <select name="kelas_id" class="w-full px-3 py-2 border border-outline-variant rounded-lg text-sm bg-white">
                <option value="">Semua Kelas</option>
                @foreach($kelasList as $k)
                <option value="{{ $k->id }}" {{ $kelasId == $k->id ? 'selected' : '' }}>{{ $k->nama }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="font-label-sm text-label-sm text-outline block mb-1">Bulan</label>
            <select name="bulan" class="w-full px-3 py-2 border border-outline-variant rounded-lg text-sm bg-white">
                @foreach(['01'=>'Jan','02'=>'Feb','03'=>'Mar','04'=>'Apr','05'=>'Mei','06'=>'Jun','07'=>'Jul','08'=>'Agu','09'=>'Sep','10'=>'Okt','11'=>'Nov','12'=>'Des'] as $v => $l)
                <option value="{{ $v }}" {{ $bulanAktif == $v ? 'selected' : '' }}>{{ $l }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="font-label-sm text-label-sm text-outline block mb-1">Tahun</label>
            <select name="tahun" class="w-full px-3 py-2 border border-outline-variant rounded-lg text-sm bg-white">
                @for($y = date('Y'); $y >= 2024; $y--)
                <option value="{{ $y }}" {{ $tahunAktif == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>
        </div>
        <div>
            <label class="font-label-sm text-label-sm text-outline block mb-1">Status</label>
            <select name="status" class="w-full px-3 py-2 border border-outline-variant rounded-lg text-sm bg-white">
                <option value="">Semua</option>
                <option value="lunas" {{ $statusFilter == 'lunas' ? 'selected' : '' }}>Lunas</option>
                <option value="belum" {{ $statusFilter == 'belum' ? 'selected' : '' }}>Belum</option>
                <option value="angsuran" {{ $statusFilter == 'angsuran' ? 'selected' : '' }}>Angsuran</option>
            </select>
        </div>
        <div class="flex items-end">
            <button type="submit" class="w-full px-3 py-2 bg-secondary-container text-on-secondary-container rounded-lg font-label-md hover:bg-secondary-container/70 transition-all">Filter</button>
        </div>
    </div>
</form>

<!-- Table -->
<div class="bg-white rounded-xl border border-outline-variant card-shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-surface-container-low">
                <tr>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">No</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">NISN</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Nama Siswa</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Kelas</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Bulan</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Jumlah</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Status</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Tgl Bayar</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant">
                @forelse($pembayaran as $index => $p)
                <tr class="hover:bg-surface-container transition-colors">
                    <td class="px-6 py-4">{{ $index + 1 }}</td>
                    <td class="px-6 py-4 text-sm">{{ $p->siswa->nisn ?? '-' }}</td>
                    <td class="px-6 py-4 font-semibold">{{ $p->siswa->user->name ?? '-' }}</td>
                    <td class="px-6 py-4 text-sm">{{ $p->siswa->kelas->nama ?? '-' }}</td>
                    <td class="px-6 py-4 text-sm">{{ $p->bulan }}/{{ $p->tahun }}</td>
                    <td class="px-6 py-4 font-semibold">Rp{{ number_format($p->jumlah, 0, ',', '.') }}</td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 rounded-full text-xs font-bold inline-block
                            {{ $p->status == 'lunas' ? 'bg-green-100 text-green-700' : '' }}
                            {{ $p->status == 'belum' ? 'bg-red-100 text-red-700' : '' }}
                            {{ $p->status == 'angsuran' ? 'bg-yellow-100 text-yellow-700' : '' }}">
                            {{ ucfirst($p->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm">{{ $p->tanggal_bayar ? $p->tanggal_bayar->isoFormat('D MMM YYYY') : '-' }}</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <button @click="openEdit = true; editId = {{ $p->id }}; form = { jumlah: '{{ $p->jumlah }}', status: '{{ $p->status }}', tanggal_bayar: '{{ $p->tanggal_bayar ?? '' }}' }" class="p-1.5 hover:bg-surface-container rounded-lg">
                                <span class="material-symbols-outlined text-primary text-[18px]">edit</span>
                            </button>
                            <form method="POST" action="{{ route('admin.keuangan.destroy', $p->id) }}" @submit.prevent="Swal.fire({title:'Konfirmasi',text:'Hapus pembayaran ini?',icon:'warning',showCancelButton:true,confirmButtonText:'Ya, hapus!',cancelButtonText:'Batal'}).then(r=>r.isConfirmed&&$el.submit())">
                                @csrf
                                @method('DELETE')
                                <button class="p-1.5 hover:bg-error/10 rounded-lg">
                                    <span class="material-symbols-outlined text-error text-[18px]">delete</span>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="9" class="px-6 py-8 text-center text-on-surface-variant">Belum ada data pembayaran.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Create Modal -->
<div x-show="openCreate" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" @click.away="openCreate = false">
    <div class="fixed inset-0 bg-black/40" @click="openCreate = false"></div>
    <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-lg p-8">
        <div class="flex items-center justify-between mb-6">
            <h3 class="font-headline-sm text-headline-sm">Catat Pembayaran</h3>
            <button @click="openCreate = false" class="p-2 hover:bg-surface-container rounded-lg"><span class="material-symbols-outlined">close</span></button>
        </div>
        <form action="{{ route('admin.keuangan.store') }}" method="POST" class="space-y-5">
            @csrf
            <div class="space-y-2">
                <label class="font-label-md text-label-md text-on-surface-variant">Siswa</label>
                <select x-model="form.siswa_id" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3" name="siswa_id" required>
                    @foreach($siswaList as $s)
                    <option value="{{ $s->id }}">{{ $s->user->name }} ({{ $s->nisn }})</option>
                    @endforeach
                </select>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">Bulan</label>
                    <select x-model="form.bulan" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3" name="bulan" required>
                        @foreach(['01'=>'Januari','02'=>'Februari','03'=>'Maret','04'=>'April','05'=>'Mei','06'=>'Juni','07'=>'Juli','08'=>'Agustus','09'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember'] as $v => $l)
                        <option value="{{ $v }}">{{ $l }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">Tahun</label>
                    <input x-model="form.tahun" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3" type="text" name="tahun" required>
                </div>
            </div>
            <div class="space-y-2">
                <label class="font-label-md text-label-md text-on-surface-variant">Jumlah</label>
                <input x-model="form.jumlah" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3" type="number" name="jumlah" required>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">Status</label>
                    <select x-model="form.status" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3" name="status" required>
                        <option value="lunas">Lunas</option>
                        <option value="belum">Belum</option>
                        <option value="angsuran">Angsuran</option>
                    </select>
                </div>
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">Tanggal Bayar</label>
                    <input x-model="form.tanggal_bayar" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3" type="date" name="tanggal_bayar">
                </div>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="button" @click="openCreate = false" class="flex-1 py-3 border border-outline-variant rounded-xl font-label-md">Batal</button>
                <button type="submit" class="flex-1 py-3 bg-primary text-on-primary rounded-xl font-label-md">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div x-show="openEdit" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" @click.away="openEdit = false">
    <div class="fixed inset-0 bg-black/40" @click="openEdit = false"></div>
    <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-lg p-8">
        <div class="flex items-center justify-between mb-6">
            <h3 class="font-headline-sm text-headline-sm">Edit Pembayaran</h3>
            <button @click="openEdit = false" class="p-2 hover:bg-surface-container rounded-lg"><span class="material-symbols-outlined">close</span></button>
        </div>
        <form method="POST" action="{{ route('admin.keuangan.update', '__ID__') }}" x-bind:action="'{{ route('admin.keuangan.update', '__ID__') }}'.replace('__ID__', editId)" class="space-y-5">
            @csrf
            @method('PUT')
            <div class="space-y-2">
                <label class="font-label-md text-label-md text-on-surface-variant">Jumlah</label>
                <input x-model="form.jumlah" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3" type="number" name="jumlah" required>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">Status</label>
                    <select x-model="form.status" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3" name="status" required>
                        <option value="lunas">Lunas</option>
                        <option value="belum">Belum</option>
                        <option value="angsuran">Angsuran</option>
                    </select>
                </div>
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant">Tanggal Bayar</label>
                    <input x-model="form.tanggal_bayar" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3" type="date" name="tanggal_bayar">
                </div>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="button" @click="openEdit = false" class="flex-1 py-3 border border-outline-variant rounded-xl font-label-md">Batal</button>
                <button type="submit" class="flex-1 py-3 bg-primary text-on-primary rounded-xl font-label-md">Simpan</button>
            </div>
        </form>
    </div>
</div>
</div>
</x-layouts.admin>

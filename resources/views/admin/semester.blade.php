<x-layouts.admin title="Kalender Akademik">
<div x-data="{ showSemesterModal: false, showEventModal: false }">
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
        <h2 class="font-headline-lg text-headline-lg text-primary">Kalender Akademik</h2>
        <p class="text-on-surface-variant font-body-md">Kelola semester dan acara akademik</p>
    </div>
    <div class="flex gap-2">
        <button @click="showSemesterModal = true" class="inline-flex items-center gap-2 bg-primary text-on-primary px-4 py-2 rounded-xl font-label-md hover:bg-primary-container transition-colors">
            <span class="material-symbols-outlined">add</span>
            Tambah Semester
        </button>
        <button @click="showEventModal = true" class="inline-flex items-center gap-2 bg-secondary text-on-secondary px-4 py-2 rounded-xl font-label-md hover:bg-secondary-container transition-colors">
            <span class="material-symbols-outlined">event</span>
            Tambah Acara
        </button>
    </div>
</div>

<div>
    <div x-show="showSemesterModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/40" @click.self="showSemesterModal = false">
        <div class="bg-white rounded-2xl p-6 w-full max-w-lg mx-4 shadow-xl">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-headline-md text-headline-md text-primary">Tambah Semester</h3>
                <button @click="showSemesterModal = false" class="text-outline hover:text-on-surface">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <form method="POST" action="{{ route('admin.semester.store') }}" class="space-y-4">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-2">
                        <label class="font-label-sm text-label-sm text-outline mb-1 block">Nama Semester</label>
                        <input type="text" name="nama" required class="w-full px-3 py-2 border border-outline-variant rounded-lg text-sm focus:ring-2 focus:ring-primary focus:border-primary outline-none" placeholder="Semester Ganjil 2025/2026">
                    </div>
                    <div>
                        <label class="font-label-sm text-label-sm text-outline mb-1 block">Tahun Ajaran</label>
                        <input type="text" name="tahun_ajaran" required class="w-full px-3 py-2 border border-outline-variant rounded-lg text-sm focus:ring-2 focus:ring-primary focus:border-primary outline-none" placeholder="2025/2026">
                    </div>
                    <div>
                        <label class="font-label-sm text-label-sm text-outline mb-1 block">Semester</label>
                        <select name="semester" class="w-full px-3 py-2 border border-outline-variant rounded-lg text-sm focus:ring-2 focus:ring-primary focus:border-primary outline-none bg-white">
                            <option value="Ganjil">Ganjil</option>
                            <option value="Genap">Genap</option>
                        </select>
                    </div>
                    <div>
                        <label class="font-label-sm text-label-sm text-outline mb-1 block">Tanggal Mulai</label>
                        <input type="date" name="tanggal_mulai" required class="w-full px-3 py-2 border border-outline-variant rounded-lg text-sm focus:ring-2 focus:ring-primary focus:border-primary outline-none">
                    </div>
                    <div>
                        <label class="font-label-sm text-label-sm text-outline mb-1 block">Tanggal Selesai</label>
                        <input type="date" name="tanggal_selesai" required class="w-full px-3 py-2 border border-outline-variant rounded-lg text-sm focus:ring-2 focus:ring-primary focus:border-primary outline-none">
                    </div>
                </div>
                <button type="submit" class="w-full bg-primary text-on-primary py-2.5 rounded-xl font-label-md hover:bg-primary-container transition-colors">Simpan</button>
            </form>
        </div>
    </div>

    <div x-show="showEventModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/40" @click.self="showEventModal = false">
        <div class="bg-white rounded-2xl p-6 w-full max-w-lg mx-4 shadow-xl">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-headline-md text-headline-md text-primary">Tambah Acara</h3>
                <button @click="showEventModal = false" class="text-outline hover:text-on-surface">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <form method="POST" action="{{ route('admin.semester.event.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="font-label-sm text-label-sm text-outline mb-1 block">Judul Acara</label>
                    <input type="text" name="judul" required class="w-full px-3 py-2 border border-outline-variant rounded-lg text-sm focus:ring-2 focus:ring-primary focus:border-primary outline-none">
                </div>
                <div>
                    <label class="font-label-sm text-label-sm text-outline mb-1 block">Deskripsi</label>
                    <textarea name="deskripsi" rows="3" class="w-full px-3 py-2 border border-outline-variant rounded-lg text-sm focus:ring-2 focus:ring-primary focus:border-primary outline-none"></textarea>
                </div>
                <div>
                    <label class="font-label-sm text-label-sm text-outline mb-1 block">Semester</label>
                    <select name="semester_id" class="w-full px-3 py-2 border border-outline-variant rounded-lg text-sm focus:ring-2 focus:ring-primary focus:border-primary outline-none bg-white">
                        <option value="">Tanpa Semester</option>
                        @foreach($semesters as $s)
                        <option value="{{ $s->id }}">{{ $s->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="font-label-sm text-label-sm text-outline mb-1 block">Tanggal Mulai</label>
                        <input type="date" name="tanggal_mulai" required class="w-full px-3 py-2 border border-outline-variant rounded-lg text-sm focus:ring-2 focus:ring-primary focus:border-primary outline-none">
                    </div>
                    <div>
                        <label class="font-label-sm text-label-sm text-outline mb-1 block">Tanggal Selesai</label>
                        <input type="date" name="tanggal_selesai" class="w-full px-3 py-2 border border-outline-variant rounded-lg text-sm focus:ring-2 focus:ring-primary focus:border-primary outline-none">
                    </div>
                </div>
                <div>
                    <label class="font-label-sm text-label-sm text-outline mb-1 block">Tipe</label>
                    <select name="tipe" class="w-full px-3 py-2 border border-outline-variant rounded-lg text-sm focus:ring-2 focus:ring-primary focus:border-primary outline-none bg-white">
                        <option value="academic">Akademik</option>
                        <option value="holiday">Libur</option>
                        <option value="exam">Ujian</option>
                        <option value="registration">Pendaftaran</option>
                        <option value="other">Lainnya</option>
                    </select>
                </div>
                <button type="submit" class="w-full bg-primary text-on-primary py-2.5 rounded-xl font-label-md hover:bg-primary-container transition-colors">Simpan</button>
            </form>
        </div>
    </div>
</div>

<div class="space-y-6">
    <div class="bg-white rounded-xl border border-outline-variant card-shadow overflow-hidden">
        <div class="p-4 border-b border-outline-variant bg-surface-bright">
            <h3 class="font-label-lg text-label-lg text-primary">Data Semester</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-primary text-on-primary">
                        <th class="text-left py-3 px-4 font-label-md">Nama</th>
                        <th class="text-left py-3 px-4 font-label-md">Tahun Ajaran</th>
                        <th class="text-left py-3 px-4 font-label-md">Semester</th>
                        <th class="text-left py-3 px-4 font-label-md">Tanggal</th>
                        <th class="text-center py-3 px-4 font-label-md">Status</th>
                        <th class="text-center py-3 px-4 font-label-md">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($semesters as $semester)
                    <tr class="border-t border-outline-variant hover:bg-surface-bright transition-colors">
                        <td class="py-3 px-4 font-body-md">{{ $semester->nama }}</td>
                        <td class="py-3 px-4">{{ $semester->tahun_ajaran }}</td>
                        <td class="py-3 px-4">{{ $semester->semester }}</td>
                        <td class="py-3 px-4 text-on-surface-variant">{{ $semester->tanggal_mulai->format('d/m/Y') }} - {{ $semester->tanggal_selesai->format('d/m/Y') }}</td>
                        <td class="py-3 px-4 text-center">
                            @if($semester->is_active)
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-green-100 text-green-700 rounded-full text-xs font-medium">Aktif</span>
                            @else
                            <a href="{{ route('admin.semester.set-active', $semester) }}" class="inline-flex items-center gap-1 px-2 py-0.5 bg-outline/10 text-outline rounded-full text-xs hover:bg-primary hover:text-on-primary transition-colors">Set Aktif</a>
                            @endif
                        </td>
                        <td class="py-3 px-4 text-center">
                            <form method="POST" action="{{ route('admin.semester.destroy', $semester) }}" class="inline" @submit.prevent="Swal.fire({title:'Konfirmasi',text:'Hapus semester ini?',icon:'warning',showCancelButton:true,confirmButtonText:'Ya, hapus!',cancelButtonText:'Batal'}).then(r=>r.isConfirmed&&$el.submit())">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-error hover:text-red-700">
                                    <span class="material-symbols-outlined text-lg">delete</span>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="py-8 text-center text-outline">Belum ada data semester</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-outline-variant card-shadow overflow-hidden">
        <div class="p-4 border-b border-outline-variant bg-surface-bright">
            <h3 class="font-label-lg text-label-lg text-primary">Acara Kalender</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-primary text-on-primary">
                        <th class="text-left py-3 px-4 font-label-md">Judul</th>
                        <th class="text-left py-3 px-4 font-label-md">Tipe</th>
                        <th class="text-left py-3 px-4 font-label-md">Semester</th>
                        <th class="text-left py-3 px-4 font-label-md">Tanggal</th>
                        <th class="text-center py-3 px-4 font-label-md">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kalender as $event)
                    <tr class="border-t border-outline-variant hover:bg-surface-bright transition-colors">
                        <td class="py-3 px-4 font-body-md">{{ $event->judul }}</td>
                        <td class="py-3 px-4">
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium
                                @switch($event->tipe)
                                    @case('holiday') bg-purple-100 text-purple-700 @break
                                    @case('exam') bg-orange-100 text-orange-700 @break
                                    @case('registration') bg-blue-100 text-blue-700 @break
                                    @default bg-gray-100 text-gray-700
                                @endswitch">{{ ucfirst($event->tipe) }}</span>
                        </td>
                        <td class="py-3 px-4">{{ $event->semester?->nama ?? '-' }}</td>
                        <td class="py-3 px-4 text-on-surface-variant">{{ $event->tanggal_mulai->format('d/m/Y') }}{{ $event->tanggal_selesai ? ' - '.$event->tanggal_selesai->format('d/m/Y') : '' }}</td>
                        <td class="py-3 px-4 text-center">
                            <form method="POST" action="{{ route('admin.semester.event.destroy', $event) }}" class="inline" @submit.prevent="Swal.fire({title:'Konfirmasi',text:'Hapus acara ini?',icon:'warning',showCancelButton:true,confirmButtonText:'Ya, hapus!',cancelButtonText:'Batal'}).then(r=>r.isConfirmed&&$el.submit())">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-error hover:text-red-700">
                                    <span class="material-symbols-outlined text-lg">delete</span>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="py-8 text-center text-outline">Belum ada acara kalender</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>
</x-layouts.admin>

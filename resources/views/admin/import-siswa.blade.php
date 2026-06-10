<x-layouts.admin title="Import Siswa">
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
        <h2 class="font-headline-lg text-headline-lg text-primary">Import Data Siswa</h2>
        <p class="text-on-surface-variant font-body-md">Import data siswa dari file CSV</p>
    </div>
    <a href="{{ route('admin.data-siswa') }}" class="inline-flex items-center gap-2 bg-outline/10 text-outline px-4 py-2 rounded-xl font-label-md hover:bg-primary hover:text-on-primary transition-colors">
        <span class="material-symbols-outlined">arrow_back</span>
        Kembali
    </a>
</div>

@if(!$preview)
<div class="bg-white rounded-xl border border-outline-variant card-shadow p-6 max-w-2xl">
    <div class="mb-6">
        <h3 class="font-headline-sm text-headline-sm text-primary mb-2">Format CSV</h3>
        <p class="text-sm text-on-surface-variant mb-3">Baris pertama harus berisi header dengan kolom berikut:</p>
        <code class="block bg-surface-container-low p-3 rounded-lg text-xs font-mono mb-3">nama,nisn,nis,email,kelas,status</code>
        <p class="text-sm text-on-surface-variant">Contoh baris data:</p>
        <code class="block bg-surface-container-low p-3 rounded-lg text-xs font-mono">Budi Santoso,1234567890,2024001,budi@example.com,X IPA 1,aktif</code>
    </div>

    <form method="POST" action="{{ route('admin.import.siswa.preview') }}" enctype="multipart/form-data" class="space-y-4">
        @csrf
        <div>
            <label class="font-label-sm text-label-sm text-outline mb-1 block">File CSV</label>
            <input type="file" name="file" required accept=".csv,.txt" class="w-full px-3 py-2 border border-outline-variant rounded-lg text-sm focus:ring-2 focus:ring-primary focus:border-primary outline-none">
        </div>
        <button type="submit" class="bg-primary text-on-primary px-6 py-2.5 rounded-xl font-label-md hover:bg-primary-container transition-colors">
            <span class="material-symbols-outlined text-[18px]">preview</span>
            Preview Data
        </button>
    </form>
</div>
@else
<div class="bg-white rounded-xl border border-outline-variant card-shadow p-6">
    <div class="flex items-center justify-between mb-4">
        <div>
            <h3 class="font-headline-sm text-headline-sm text-primary">Preview Data</h3>
            <p class="text-sm text-on-surface-variant">Total {{ count($rows) }} baris — {{ $validCount }} valid, {{ $errorCount }} dengan error</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.import.siswa') }}" class="px-4 py-2 bg-surface-container-high text-on-surface rounded-lg font-label-md hover:bg-surface-container-higher transition-colors">Kembali</a>
            <form method="POST" action="{{ route('admin.import.siswa') }}">
                @csrf
                <button type="submit" class="px-4 py-2 bg-primary text-on-primary rounded-lg font-label-md hover:bg-primary-container transition-colors">
                    <span class="material-symbols-outlined text-[16px]">upload</span>
                    Konfirmasi & Import
                </button>
            </form>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-primary text-on-primary">
                    <th class="text-left py-3 px-4 font-label-md">#</th>
                    <th class="text-left py-3 px-4 font-label-md">Nama</th>
                    <th class="text-left py-3 px-4 font-label-md">NISN</th>
                    <th class="text-left py-3 px-4 font-label-md">NIS</th>
                    <th class="text-left py-3 px-4 font-label-md">Email</th>
                    <th class="text-left py-3 px-4 font-label-md">Kelas</th>
                    <th class="text-left py-3 px-4 font-label-md">Status</th>
                    <th class="text-center py-3 px-4 font-label-md">Validasi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rows as $i => $row)
                @php $rowNum = $i + 1; $rowErrors = $errors[$rowNum] ?? []; @endphp
                <tr class="border-t border-outline-variant {{ empty($rowErrors) ? 'hover:bg-surface-bright' : 'bg-red-50' }} transition-colors">
                    <td class="py-3 px-4 text-on-surface-variant">{{ $rowNum }}</td>
                    <td class="py-3 px-4 {{ empty($row['nama']) ? 'text-red-600' : '' }}">{{ $row['nama'] ?? '-' }}</td>
                    <td class="py-3 px-4 {{ empty($row['nisn']) ? 'text-red-600' : '' }}">{{ $row['nisn'] ?? '-' }}</td>
                    <td class="py-3 px-4">{{ $row['nis'] ?? '-' }}</td>
                    <td class="py-3 px-4 text-on-surface-variant">{{ $row['email'] ?? '-' }}</td>
                    <td class="py-3 px-4">{{ $row['kelas'] ?? '-' }}</td>
                    <td class="py-3 px-4">{{ $row['status'] ?? 'aktif' }}</td>
                    <td class="py-3 px-4 text-center">
                        @if(empty($rowErrors))
                        <span class="text-green-600 text-xs font-medium">OK</span>
                        @else
                        <span x-data @click="$dispatch('open-tooltip', 'error-{{ $rowNum }}')" class="text-red-600 text-xs font-medium cursor-help">
                            {{ count($rowErrors) }} error
                        </span>
                        <div class="hidden">
                            @foreach($rowErrors as $err)
                            <p class="text-xs text-red-600">{{ $err }}</p>
                            @endforeach
                        </div>
                        @endif
                    </td>
                </tr>
                @if(!empty($rowErrors))
                <tr class="border-t border-red-200 bg-red-50">
                    <td colspan="8" class="py-2 px-4">
                        @foreach($rowErrors as $err)
                        <p class="text-xs text-red-600">⚠ {{ $err }}</p>
                        @endforeach
                    </td>
                </tr>
                @endif
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6 flex items-center justify-between">
        <p class="text-sm text-on-surface-variant">{{ $validCount }} data valid akan diimport, {{ $errorCount }} data memiliki error dan akan dilewati.</p>
        <form method="POST" action="{{ route('admin.import.siswa') }}">
            @csrf
            <button type="submit" class="bg-primary text-on-primary px-6 py-2.5 rounded-xl font-label-md hover:bg-primary-container transition-colors">
                <span class="material-symbols-outlined text-[16px]">upload</span>
                Konfirmasi & Import
            </button>
        </form>
    </div>
</div>
@endif
</x-layouts.admin>

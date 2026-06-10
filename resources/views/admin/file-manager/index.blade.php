<x-layouts.admin title="File Manager | EduPortal">
<x-slot:styles>
<style>[x-cloak] { display: none !important; }</style>
</x-slot:styles>
<div x-data="{ uploadOpen: false, folderOpen: false, selectMode: false, selected: [] }">
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
        <h2 class="font-headline-lg text-headline-lg text-primary">File Manager</h2>
        <p class="text-on-surface-variant font-body-md">Kelola file dan gambar publik</p>
    </div>
    <div class="flex items-center gap-2">
        <button @click="selectMode = !selectMode; if(!selectMode) selected = []"
                class="px-4 py-2.5 border border-outline-variant rounded-lg flex items-center gap-2 font-label-md hover:bg-surface-container transition-all"
                :class="selectMode ? 'bg-secondary/10 border-secondary text-secondary' : ''">
            <span class="material-symbols-outlined" x-text="selectMode ? 'close' : 'checklist'"></span>
            <span x-text="selectMode ? 'Selesai' : 'Pilih'"></span>
        </button>
        <button @click="folderOpen = true"
                class="px-4 py-2.5 border border-outline-variant rounded-lg flex items-center gap-2 font-label-md hover:bg-surface-container transition-all">
            <span class="material-symbols-outlined">create_new_folder</span>
            Folder Baru
        </button>
        <button @click="uploadOpen = true"
                class="px-4 py-2.5 bg-primary text-on-primary rounded-lg flex items-center gap-2 font-label-md hover:bg-primary/90 transition-all active:scale-95">
            <span class="material-symbols-outlined">upload</span>
            Upload File
        </button>
    </div>
</div>

<!-- Bulk Delete Bar -->
<div x-show="selected.length > 0" x-cloak
     class="mb-4 p-3 bg-error/10 border border-error/30 rounded-xl flex items-center justify-between">
    <p class="text-label-md text-error font-semibold" x-text="selected.length + ' item dipilih'"></p>
    <div class="flex items-center gap-2">
        <button @click="selected = []"
                class="px-3 py-1.5 border border-outline-variant rounded-lg text-label-sm hover:bg-surface-container transition-all">
            Batal
        </button>
        <form action="{{ route('admin.file-manager.bulk-destroy') }}" method="POST"
              onsubmit="return confirm('Hapus ' + selected.length + ' item terpilih?')">
            @csrf @method('DELETE')
            <template x-for="path in selected" :key="path">
                <input type="hidden" name="paths[]" :value="path">
            </template>
            <button type="submit"
                    class="px-3 py-1.5 bg-error text-on-error rounded-lg text-label-sm hover:bg-error/90 transition-all">
                Hapus Semua
            </button>
        </form>
    </div>
</div>

<!-- Breadcrumb -->
<nav class="flex items-center gap-1 text-sm mb-4 flex-wrap">
    @foreach($breadcrumbs as $i => $crumb)
        @if($i > 0)
            <span class="material-symbols-outlined text-sm text-outline">chevron_right</span>
        @endif
        <a href="{{ route('admin.file-manager', ['path' => $crumb['path']]) }}"
           class="px-2 py-1 rounded-lg {{ $loop->last ? 'bg-surface-container font-semibold' : 'text-on-surface-variant hover:bg-surface-container' }} transition-colors">
            {{ $crumb['label'] }}
        </a>
    @endforeach
</nav>

<!-- Upload Modal -->
<div x-show="uploadOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/40" @click.self="uploadOpen = false">
    <div class="bg-white rounded-xl p-6 w-full max-w-lg mx-4 shadow-2xl">
        <h3 class="font-headline-sm text-headline-sm text-primary mb-4">Upload File</h3>
        <form action="{{ route('admin.file-manager.upload', ['path' => $currentPath]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="file" name="files[]" multiple
                   class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary"
                   required>
            <p class="text-xs text-on-surface-variant mt-1">Maks 10MB per file</p>
            <div class="flex justify-end gap-3 mt-6">
                <button type="button" @click="uploadOpen = false"
                        class="px-4 py-2 border border-outline-variant rounded-lg font-label-md hover:bg-surface-container transition-all">
                    Batal
                </button>
                <button type="submit"
                        class="px-4 py-2 bg-primary text-on-primary rounded-lg font-label-md hover:bg-primary/90 transition-all">
                    Upload
                </button>
            </div>
        </form>
    </div>
</div>

<!-- New Folder Modal -->
<div x-show="folderOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/40" @click.self="folderOpen = false">
    <div class="bg-white rounded-xl p-6 w-full max-w-lg mx-4 shadow-2xl">
        <h3 class="font-headline-sm text-headline-sm text-primary mb-4">Buat Folder Baru</h3>
        <form action="{{ route('admin.file-manager.create-folder', ['path' => $currentPath]) }}" method="POST">
            @csrf
            <input type="text" name="name" placeholder="Nama folder"
                   class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary"
                   required>
            <div class="flex justify-end gap-3 mt-6">
                <button type="button" @click="folderOpen = false"
                        class="px-4 py-2 border border-outline-variant rounded-lg font-label-md hover:bg-surface-container transition-all">
                    Batal
                </button>
                <button type="submit"
                        class="px-4 py-2 bg-primary text-on-primary rounded-lg font-label-md hover:bg-primary/90 transition-all">
                    Buat
                </button>
            </div>
        </form>
    </div>
</div>

<!-- File Grid -->
<div class="bg-white rounded-xl border border-outline-variant card-shadow overflow-hidden">
    <div class="p-4 border-b border-outline-variant bg-surface-container">
        <p class="text-label-md text-on-surface-variant">
            <span class="font-semibold">{{ count($files) }}</span> file,
            <span class="font-semibold">{{ count(array_filter($directories, fn($d) => !$d['is_parent'])) }}</span> folder
        </p>
    </div>
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4 p-4">
        @foreach($directories as $dir)
        @if($dir['is_parent'])
        <a href="{{ route('admin.file-manager', ['path' => $dir['path']]) }}"
           class="flex flex-col items-center justify-center gap-2 p-4 rounded-xl border border-outline-variant hover:bg-surface-container transition-all opacity-60">
            <span class="material-symbols-outlined text-2xl text-secondary">arrow_upward</span>
            <span class="text-label-md font-semibold text-center truncate w-full">Kembali</span>
        </a>
        @else
        <a href="{{ route('admin.file-manager', ['path' => $dir['path']]) }}"
           class="flex flex-col items-center justify-center gap-2 p-4 rounded-xl border border-outline-variant hover:bg-surface-container transition-all group relative"
           :class="selected.includes('{{ $dir['path'] }}') ? 'ring-2 ring-secondary border-secondary' : ''"
           @click="if(selectMode) { $event.preventDefault(); let p = '{{ $dir['path'] }}'; selected = selected.includes(p) ? selected.filter(x => x !== p) : [...selected, p] }">
            <span class="material-symbols-outlined text-3xl text-secondary">folder</span>
            <span class="text-label-md font-semibold text-center truncate w-full">{{ $dir['name'] }}</span>
            <div x-show="selectMode"
                 class="absolute top-1 left-1"
                 @click.prevent>
                <input type="checkbox"
                       :checked="selected.includes('{{ $dir['path'] }}')"
                       @click.stop="let p = '{{ $dir['path'] }}'; selected = selected.includes(p) ? selected.filter(x => x !== p) : [...selected, p]"
                       class="w-4 h-4 rounded border-outline text-secondary focus:ring-secondary cursor-pointer">
            </div>
            <form action="{{ route('admin.file-manager.destroy') }}" method="POST"
                  onclick="event.stopPropagation()"
                  onsubmit="return confirm('Hapus folder {{ $dir['name'] }}?')"
                  class="absolute top-1 right-1 opacity-0 group-hover:opacity-100"
                  x-show="!selectMode">
                @csrf @method('DELETE')
                <input type="hidden" name="path" value="{{ $dir['path'] }}">
                <button type="submit"
                        class="flex items-center justify-center p-1.5 bg-white rounded-lg shadow hover:bg-error/10 transition-colors"
                        title="Hapus folder">
                    <span class="material-symbols-outlined text-sm text-error">delete</span>
                </button>
            </form>
        </a>
        @endif
        @endforeach

        @forelse($files as $file)
        <div class="flex flex-col items-stretch gap-2 p-4 rounded-xl border border-outline-variant hover:bg-surface-container transition-all relative"
             :class="selected.includes('{{ $file['path'] }}') ? 'ring-2 ring-secondary border-secondary' : ''"
             @click="if(selectMode) { let p = '{{ $file['path'] }}'; selected = selected.includes(p) ? selected.filter(x => x !== p) : [...selected, p] }">
            <div x-show="selectMode"
                 class="absolute top-1 left-1 z-10"
                 @click.stop>
                <input type="checkbox"
                       :checked="selected.includes('{{ $file['path'] }}')"
                       @click.stop="let p = '{{ $file['path'] }}'; selected = selected.includes(p) ? selected.filter(x => x !== p) : [...selected, p]"
                       class="w-4 h-4 rounded border-outline text-secondary focus:ring-secondary cursor-pointer">
            </div>
            <div class="w-full h-24 flex items-center justify-center bg-surface-container-low rounded-lg overflow-hidden">
                @if($file['is_image'])
                <img src="{{ $file['url'] }}" alt="{{ $file['name'] }}"
                     class="w-full h-full object-cover"
                     loading="lazy"
                     onerror="this.remove();this.nextElementSibling.classList.remove('hidden')">
                <div class="hidden w-full h-full items-center justify-center bg-surface-container-low rounded-lg">
                    <span class="material-symbols-outlined text-2xl text-outline">broken_image</span>
                </div>
                @else
                <span class="material-symbols-outlined text-2xl text-outline">description</span>
                @endif
            </div>
            <div class="w-full flex items-center justify-between gap-1">
                <span class="text-label-sm text-on-surface-variant truncate flex-1">
                    @php $size = $file['size']; @endphp
                    {{ $size > 1048576 ? round($size / 1048576, 2).' MB' : ($size > 1024 ? round($size / 1024, 1).' KB' : $size.' B') }}
                </span>
                @if($file['is_image'])
                <a href="{{ $file['url'] }}" target="_blank"
                   class="p-1 hover:bg-surface-container rounded transition-colors" title="Lihat">
                    <span class="material-symbols-outlined text-lg text-outline">open_in_new</span>
                </a>
                @endif
                <a href="{{ $file['url'] }}" download
                   class="p-1 hover:bg-surface-container rounded transition-colors" title="Download">
                    <span class="material-symbols-outlined text-lg text-outline">download</span>
                </a>
                <form action="{{ route('admin.file-manager.destroy') }}" method="POST"
                      onsubmit="return confirm('Hapus file {{ $file['name'] }}?')" class="inline">
                    @csrf @method('DELETE')
                    <input type="hidden" name="path" value="{{ $file['path'] }}">
                    <button type="submit"
                            class="p-1 hover:bg-surface-container rounded transition-colors" title="Hapus">
                        <span class="material-symbols-outlined text-lg text-error">delete</span>
                    </button>
                </form>
            </div>
            <span class="text-label-sm font-semibold text-center truncate w-full">{{ $file['name'] }}</span>
        </div>
        @empty
            @if(count($directories) === 0 || (count($directories) === 1 && $directories[0]['is_parent']))
            <div class="col-span-full py-12 text-center text-on-surface-variant">
                <span class="material-symbols-outlined text-2xl mb-2 block">folder_open</span>
                <p class="text-body-md">Folder ini kosong</p>
            </div>
            @endif
        @endforelse
    </div>
</div>
</div>
</x-layouts.admin>

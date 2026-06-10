@props([
    'name',
    'title' => 'Konfirmasi Hapus',
    'message' => 'Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.',
    'action' => '',
    'id' => '',
])

<x-modal :name="$name" maxWidth="md">
    <div class="p-6">
        <div class="flex items-center gap-4 mb-4">
            <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                <span class="material-symbols-outlined text-red-600 text-2xl">delete_forever</span>
            </div>
            <div>
                <h3 class="font-headline-sm text-headline-sm text-gray-900">{{ $title }}</h3>
                <p class="text-sm text-gray-500 mt-1">{{ $message }}</p>
            </div>
        </div>
        <div class="flex justify-end gap-3 mt-6">
            <button @click="show = false" type="button" class="px-4 py-2 border border-gray-300 rounded-lg font-label-md hover:bg-gray-50 transition-all">
                Batal
            </button>
            <form method="POST" :action="'{{ $action }}'.replace('__ID__', {{ $id }})" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg font-label-md hover:bg-red-700 transition-all">
                    Hapus
                </button>
            </form>
        </div>
    </div>
</x-modal>

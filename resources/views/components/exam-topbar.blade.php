@props(['ujian' => null])
<header class="flex justify-between items-center h-14 px-margin-mobile md:px-margin-desktop w-full bg-primary-container text-on-primary-container fixed top-0 z-50 shadow-md">
    <div class="flex items-center gap-4">
        <button id="sidebar-toggle" class="lg:hidden p-1.5 hover:bg-primary-fixed rounded-lg transition-colors" onclick="document.getElementById('soal-sidebar')?.classList.toggle('hidden')">
            <span class="material-symbols-outlined">menu</span>
        </button>
        <h1 class="font-headline-sm text-headline-sm font-semibold text-on-primary-container">{{ $ujian?->nama ?? 'Ujian' }}</h1>
    </div>
    <div class="flex items-center gap-6">
        <button class="bg-secondary-container text-on-secondary-container px-6 py-1.5 rounded-lg font-label-md text-label-md font-bold hover:opacity-90 active:scale-95 transition-all" onclick="document.getElementById('form-submit')?.dispatchEvent(new Event('submit'))">
            Selesai Ujian
        </button>
    </div>
</header>

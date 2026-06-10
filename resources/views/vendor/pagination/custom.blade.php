@if ($paginator->hasPages())
    <nav class="flex items-center justify-between">
        <div class="flex-1 flex items-center gap-1">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span class="px-3 py-2 text-sm text-outline border border-outline-variant rounded-lg opacity-50 cursor-not-allowed flex items-center gap-1">
                    <span class="material-symbols-outlined text-[16px]">chevron_left</span>
                    Sebelumnya
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="px-3 py-2 text-sm text-on-surface border border-outline-variant rounded-lg hover:bg-surface-container transition-colors flex items-center gap-1">
                    <span class="material-symbols-outlined text-[16px]">chevron_left</span>
                    Sebelumnya
                </a>
            @endif

            {{-- Pagination Elements --}}
            <div class="hidden sm:flex items-center gap-1 mx-2">
                @foreach ($elements as $element)
                    {{-- "Three Dots" Separator --}}
                    @if (is_string($element))
                        <span class="px-3 py-2 text-sm text-outline">{{ $element }}</span>
                    @endif

                    {{-- Array Of Links --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span class="px-3 py-2 text-sm bg-primary text-on-primary rounded-lg font-bold min-w-[36px] text-center">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="px-3 py-2 text-sm text-on-surface border border-outline-variant rounded-lg hover:bg-surface-container transition-colors min-w-[36px] text-center">{{ $page }}</a>
                            @endif
                        @endforeach
                    @endif
                @endforeach
            </div>

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="px-3 py-2 text-sm text-on-surface border border-outline-variant rounded-lg hover:bg-surface-container transition-colors flex items-center gap-1">
                    Selanjutnya
                    <span class="material-symbols-outlined text-[16px]">chevron_right</span>
                </a>
            @else
                <span class="px-3 py-2 text-sm text-outline border border-outline-variant rounded-lg opacity-50 cursor-not-allowed flex items-center gap-1">
                    Selanjutnya
                    <span class="material-symbols-outlined text-[16px]">chevron_right</span>
                </span>
            @endif
        </div>
        <div class="hidden sm:flex items-center gap-1 text-sm text-on-surface-variant ml-4">
            <span>Halaman {{ $paginator->currentPage() }} dari {{ $paginator->lastPage() }}</span>
        </div>
    </nav>
@endif

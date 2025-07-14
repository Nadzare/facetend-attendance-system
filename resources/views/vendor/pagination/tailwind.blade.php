@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex items-center justify-between mt-6">
        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between w-full">
            <div>
                <p class="text-sm text-gray-700 dark:text-gray-400">
                    {!! __('Menampilkan') !!}
                    <span class="font-medium">{{ $paginator->firstItem() }}</span>
                    {!! __('hingga') !!}
                    <span class="font-medium">{{ $paginator->lastItem() }}</span>
                    {!! __('dari') !!}
                    <span class="font-medium">{{ $paginator->total() }}</span>
                    {!! __('entri') !!}
                </p>
            </div>

            <div>
                <span class="relative z-0 inline-flex rounded-md shadow-sm">
                    {{-- Tombol Sebelumnya --}}
                    @if ($paginator->onFirstPage())
                        <span class="relative inline-flex items-center px-3 py-2 text-sm font-medium text-gray-400 bg-gray-100 border border-gray-300 rounded-l-md cursor-default">
                            <i class="fas fa-angle-left"></i>
                        </span>
                    @else
                        <a href="{{ $paginator->previousPageUrl() }}" rel="prev"
                           class="relative inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-gradient-to-r from-[#023392] to-[#266EAF] border border-blue-700 rounded-l-md hover:opacity-90 transition">
                            <i class="fas fa-angle-left"></i>
                        </a>
                    @endif

                    {{-- Halaman --}}
                    @foreach ($elements as $element)
                        {{-- Separator Tiga Titik --}}
                        @if (is_string($element))
                            <span class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-500 bg-white border border-gray-300">
                                {{ $element }}
                            </span>
                        @endif

                        {{-- Link Halaman --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <span aria-current="page">
                                        <span class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-bold text-white bg-gradient-to-r from-[#023392] to-[#266EAF] border border-blue-700 shadow rounded-md">
                                            {{ $page }}
                                        </span>
                                    </span>
                                @else
                                    <a href="{{ $url }}"
                                       class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-blue-700 bg-white border border-blue-300 rounded-md hover:bg-blue-50 hover:text-[#023392] transition">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Tombol Berikutnya --}}
                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->nextPageUrl() }}" rel="next"
                           class="relative inline-flex items-center px-3 py-2 -ml-px text-sm font-medium text-white bg-gradient-to-r from-[#023392] to-[#266EAF] border border-blue-700 rounded-r-md hover:opacity-90 transition">
                            <i class="fas fa-angle-right"></i>
                        </a>
                    @else
                        <span class="relative inline-flex items-center px-3 py-2 -ml-px text-sm font-medium text-gray-400 bg-gray-100 border border-gray-300 rounded-r-md cursor-default">
                            <i class="fas fa-angle-right"></i>
                        </span>
                    @endif
                </span>
            </div>
        </div>
    </nav>
@endif

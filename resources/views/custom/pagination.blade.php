@if ($paginator->hasPages())
    <div class="flex items-center justify-center space-x-1 mt-8">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="px-3 py-2 text-sm leading-4 text-gray-400 border border-gray-300 rounded-md cursor-not-allowed bg-gray-100">
                ← Previous
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="px-3 py-2 text-sm leading-4 text-gray-600 border border-gray-300 rounded-md hover:bg-gray-50 transition-colors">
                ← Previous
            </a>
        @endif

        {{-- Page Numbers --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <span class="px-3 py-2 text-sm leading-4 text-gray-600">{{ $element }}</span>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="px-3 py-2 text-sm leading-4 text-white bg-indigo-600 border border-indigo-600 rounded-md font-medium">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="px-3 py-2 text-sm leading-4 text-gray-600 border border-gray-300 rounded-md hover:bg-gray-50 transition-colors">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="px-3 py-2 text-sm leading-4 text-gray-600 border border-gray-300 rounded-md hover:bg-gray-50 transition-colors">
                Next →
            </a>
        @else
            <span class="px-3 py-2 text-sm leading-4 text-gray-400 border border-gray-300 rounded-md cursor-not-allowed bg-gray-100">
                Next →
            </span>
        @endif
    </div>
@endif
@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-center">
        <ul class="inline-flex items-center gap-1.5">
            {{-- Previous --}}
            <li>
                @if ($paginator->onFirstPage())
                    <span aria-disabled="true" aria-label="@lang('pagination.previous')"
                          class="inline-flex items-center gap-2 h-11 px-4 rounded-xl text-sm font-medium text-theme-tertiary bg-theme-secondary border border-theme opacity-50 cursor-not-allowed">
                        <i class="fa-duotone fa-light fa-arrow-left"></i><span class="hidden sm:inline">Prev</span>
                    </span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')"
                       class="inline-flex items-center gap-2 h-11 px-4 rounded-xl text-sm font-medium text-theme-secondary bg-theme-secondary border border-theme hover:border-primary-400 hover:text-primary-600 dark:hover:text-primary-400 hover:-translate-x-0.5 transition-all shadow-sm">
                        <i class="fa-duotone fa-light fa-arrow-left"></i><span class="hidden sm:inline">Prev</span>
                    </a>
                @endif
            </li>

            {{-- Numbered links (desktop) --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <li class="hidden sm:block">
                        <span class="inline-flex items-center justify-center w-11 h-11 text-theme-tertiary select-none">&hellip;</span>
                    </li>
                @endif
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        <li class="hidden sm:block">
                            @if ($page == $paginator->currentPage())
                                <span aria-current="page"
                                      class="inline-flex items-center justify-center w-11 h-11 rounded-xl text-sm font-bold text-white bg-accent-500 shadow-md">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" aria-label="Go to page {{ $page }}"
                                   class="inline-flex items-center justify-center w-11 h-11 rounded-xl text-sm font-medium text-theme-secondary bg-theme-secondary border border-theme hover:border-primary-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">{{ $page }}</a>
                            @endif
                        </li>
                    @endforeach
                @endif
            @endforeach

            {{-- Compact page indicator (mobile) --}}
            <li class="sm:hidden">
                <span class="inline-flex items-center h-11 px-4 rounded-xl text-sm font-medium text-theme-secondary bg-theme-secondary border border-theme">
                    {{ $paginator->currentPage() }} <span class="mx-1 text-theme-tertiary">/</span> {{ $paginator->lastPage() }}
                </span>
            </li>

            {{-- Next --}}
            <li>
                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')"
                       class="inline-flex items-center gap-2 h-11 px-4 rounded-xl text-sm font-medium text-theme-secondary bg-theme-secondary border border-theme hover:border-primary-400 hover:text-primary-600 dark:hover:text-primary-400 hover:translate-x-0.5 transition-all shadow-sm">
                        <span class="hidden sm:inline">Next</span><i class="fa-duotone fa-light fa-arrow-right"></i>
                    </a>
                @else
                    <span aria-disabled="true" aria-label="@lang('pagination.next')"
                          class="inline-flex items-center gap-2 h-11 px-4 rounded-xl text-sm font-medium text-theme-tertiary bg-theme-secondary border border-theme opacity-50 cursor-not-allowed">
                        <span class="hidden sm:inline">Next</span><i class="fa-duotone fa-light fa-arrow-right"></i>
                    </span>
                @endif
            </li>
        </ul>
    </nav>
@endif

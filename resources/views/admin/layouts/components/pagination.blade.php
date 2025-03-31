<div class="col-sm-12 col-md-5">
    <div class="dataTables_info" id="customerList-table_info" role="status" aria-live="polite">
        Showing {{ $paginator->firstItem() }} to {{ $paginator->lastItem() }} of {{ $paginator->total() }} entries
    </div>
</div>

<div class="col-sm-12 col-md-7">
    <div class="dataTables_paginate paging_simple_numbers pagination-rounded" id="customerList-table_paginate">
        <ul class="pagination">

            @if ($paginator->onFirstPage())
            <li class="paginate_button page-item previous disabled" id="customerList-table_previous">
                <a aria-controls="customerList-table" aria-disabled="true" role="link" data-dt-idx="previous"
                    tabindex="-1" class="page-link">
                    <i class="mdi mdi-chevron-left"></i>
                </a>
            </li>
            @else
            <li class="paginate_button page-item previous" id="customerList-table_previous">
                <a href="{{ $paginator->previousPageUrl() }}" aria-controls="customerList-table" class="page-link">
                    <i class="mdi mdi-chevron-left"></i>
                </a>
            </li>
            @endif

            @foreach ($elements as $element)
            @if (is_string($element))
            <li class="paginate_button page-item active">
                <a aria-controls="customerList-table" class="page-link">
                    {{ $element }}
                </a>
            </li>
            @endif

            @if (is_array($element))
            @foreach ($element as $page => $url)
            @if ($page == $paginator->currentPage())
            <li class="paginate_button page-item active">
                <a aria-controls="customerList-table" class="page-link">
                    {{ $page }}
                </a>
            </li>

            @else
            <li class="paginate_button page-item ">
                <a href="{{ $url }}" aria-controls="customerList-table" class="page-link">
                    {{ $page }}
                </a>
            </li>
            @endif
            @endforeach
            @endif

            @endforeach

            @if ($paginator->hasMorePages())
            <li class="paginate_button page-item next" id="customerList-table_next">
                <a href="{{ $paginator->nextPageUrl() }}" aria-controls="customerList-table" class="page-link">
                    <i class="mdi mdi-chevron-right"></i>
                </a>
            </li>
            @else
            <li class="paginate_button page-item disabled" id="customerList-table_next">
                <a href="#" aria-controls="customerList-table" role="link" data-dt-idx="next" tabindex="0"
                    class="page-link">
                    <i class="mdi mdi-chevron-right"></i>
                </a>
            </li>
            @endif
        </ul>
    </div>
</div>

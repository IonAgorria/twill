@extends('twill::layouts.main')

@section('appTypeClass', 'body--listing')
@if ($customPublishedLabel ?? false) published-label="{{ $customPublishedLabel }}" @endif
@if ($customDraftLabel ?? false) draft-label="{{ $customDraftLabel }}" @endif
@php
    if($customPublishedLabel ?? true){
        $customPublishedLabel = __('navigation.live') == 'navigation.live' ? 'Live' : __('navigation.live');;
    }
    if($customDraftLabel ?? true){
        $customDraftLabel = __('navigation.todraft') == 'navigation.todraft' ? 'Draft' : __('navigation.todraft');;
    }
    $translate = $translate ?? false;
    $translateTitle = $translateTitle ?? $translate ?? false;
    $reorder = $reorder ?? false;
    $nested = $nested ?? false;
    $bulkEdit = $bulkEdit ?? true;
    $create = $create ?? false;
    $searchPlaceholder = __('navigation.search') == 'navigation.search' ? 'Search' : __('navigation.search');
    $deleteModalTitle = __('navigation.deleteitem')=='navigation.deleteitem'?'Delete item': __('navigation.deleteitem');
    $toTrash = __('navigation.totrash')=='navigation.totrash'?'Move to trash': __('navigation.totrash');
    $toTrashInfo = __('navigation.softdelinfo')=='navigation.softdelinfo'?'The item won\'t be deleted but moved to trash.': __('navigation.softdelinfo');
    $deleteLabel = __('navigation.delete')=='navigation.delete'?'Delete': __('navigation.delete');
    $cancelLabel = __('navigation.cancel')=='navigation.cancel'?'Cancel': __('navigation.cancel');
    $emptyMessage = __('navigation.noitem')=='navigation.noitem'?'There is no item here yet.': __('navigation.noitem');
    $addNewLabel = __('navigation.addnew')=='navigation.addnew'?'Add new ': __('navigation.addnew');
    $permalinkLabel  = __('navigation.viewpermalink')=='navigation.viewpermalink'?'View permalink': __('navigation.viewpermalink');
    $editLabel = __('navigation.edit')=='navigation.edit'?'Edit': __('navigation.edit');
    $publishLabel = __('navigation.publish')=='navigation.publish'?'Publish': __('navigation.publish');
    $unpublishLabel = __('navigation.unpublish')=='navigation.unpublish'?'Unpublish': __('navigation.unpublish');
    $featureLabel = __('navigation.feature')=='navigation.feature'?'Feature': __('navigation.feature');
    $unfeatureLabel = __('navigation.unfeature')=='navigation.unfeature'?'Unfeature': __('navigation.unfeature');
    $restoreLabel = __('navigation.restore')=='navigation.restore'?'Restore': __('navigation.restore');
    $deleteLabel = __('navigation.delete')=='navigation.delete'?'Delete': __('navigation.delete');
    $showLabel = __('navigation.show')=='navigation.show'?'Show': __('navigation.show');
    $ofLabel = __('navigation.of')=='navigation.of'?'of': __('navigation.of');
    $perPageLabel = __('navigation.perpage')=='navigation.perpage'?'Rows per page:': __('navigation.perpage');
    $loadingLabel = __('navigation.loading')=='navigation.loading'?'Loading': __('navigation.loading');
    $itemLabel = __('navigation.item')=='navigation.item'?'Item':__('navigation.item');
    $selectedLabel = __('navigation.selected')=='navigation.selected'?'selected':__('navigation.selected');
    $bulkActionsLabel = __('navigation.bulkactions')=='navigation.bulkactions'?'Bulk actions':__('navigation.bulkactions');
    $clearLabel = __('navigation.clearselection')=='navigation.clearselection'?'Clear':__('navigation.clearselection');
    $failedSubmissionLabel = __('navigation.failedsubmission')=='navigation.failedsubmission'?'Your submission could not be validated, please fix and retry':__('navigation.failedsubmission');
    $addedContentLabel = __('navigation.addedcontent')=='navigation.addedcontent'?'Your content has been added':__('navigation.addedcontent');
    $addedContentVariantLabel = __('navigation.success')=='navigation.success'?'success':__('navigation.success');
    $addingErrorLabel = __('navigation.addingerror')=='navigation.addingerror'?'Your content can not be added, please retry':__('navigation.addingerror');
    $addingErrorVariantLabel = __('navigation.error')=='navigation.error'?'error':__('navigation.error');
    $updateLabel = __('navigation.update')=='navigation.update'?'Update':__('navigation.update');
    $createLabel = __('publisher.create')=='publisher.create'?'Create':__('publisher.create'); ;
    $createAddAnotherLabel = __('publisher.createaddanother')=='publisher.createaddanother'?'Create and add another':__('publisher.createaddanother');;
@endphp

@section('content')
    <div class="listing">
        <div class="listing__nav">
            <div class="container" ref="form">
                <a17-filter v-on:submit="filterListing" v-bind:closed="hasBulkIds" placeholder="{{$searchPlaceholder}}"
                            initial-search-value="{{ $filters['search'] ?? '' }}" :clear-option="true"
                            v-on:clear="clearFiltersAndReloadDatas">
                    <a17-table-filters slot="navigation"></a17-table-filters>
                    @forelse($hiddenFilters as $filter)
                        @if ($loop->first)
                            <div slot="hidden-filters">
                                @endif

                                @if (isset(${$filter.'List'}))
                                    <a17-vselect
                                        name="{{ $filter }}"
                                        :addNewModalTitle="{{$addNewLabel}}"
                                        :addedContent="{{$addedContentLabel}}"
                                        :addedContentVariant="{{$addedContentVariantLabel}}"
                                        :addingError="{{$addingErrorLabel}}"
                                        :addingErrorVariant="{{$addingErrorVariantLabel}}"
                                        :options="{{ json_encode(method_exists(${$filter.'List'}, 'map') ? ${$filter.'List'}->map(function($label, $value) {
                                return [
                                    'value' => $value,
                                    'label' => $label
                                ];
                            })->values()->toArray() : ${$filter.'List'}) }}"
                                        placeholder="All {{ strtolower(str_plural($filter)) }}"
                                        ref="filterDropdown[{{ $loop->index }}]"
                                    ></a17-vselect>
                                @endif

                                @if ($loop->last)
                            </div>
                        @endif
                    @empty
                        @hasSection('hiddenFilters')
                            <div slot="hidden-filters">
                                @yield('hiddenFilters')
                            </div>
                        @endif
                    @endforelse

                    @if($create)
                        <div slot="additional-actions">
                            <a17-button variant="validate" size="small"
                                        v-on:click="create">{{$addNewLabel}}</a17-button>
                        </div>
                    @endif
                </a17-filter>
            </div>
            @if($bulkEdit)
                <a17-bulk
                    item-label="{{$itemLabel}}"
                    selected-label="{{$selectedLabel}}"
                    bulk-actions-label="{{$bulkActionsLabel}}"
                    publish-label="{{$publishLabel}}"
                    unpublish-label="{{$unpublishLabel}}"
                    feature-label="{{$featureLabel}}"
                    unfeature-label="{{$unfeatureLabel}}"
                    delete-label="{{$deleteLabel}}"
                    restore-label="{{$restoreLabel}}"
                    clear-label="{{$clearLabel}}"
                ></a17-bulk>
            @endif
        </div>
        @if($nested)
            <a17-nested-datatable
                :draggable="{{ $reorder ? 'true' : 'false' }}"
                :max-depth="{{ $nestedDepth ?? '1' }}"
                :bulkeditable="{{ $bulkEdit ? 'true' : 'false' }}"
                empty-message={{$emptyMessage}}>
            </a17-nested-datatable>
        @else
            <a17-datatable
                loading-label="{{$loadingLabel}}"
                per-page-label="{{$perPageLabel}}"
                of-label="{{$ofLabel}}"
                show-label="{{$showLabel}}"
                permalink-label="{{$permalinkLabel}}"
                edit-label="{{$editLabel}}"
                publish-label="{{$publishLabel}}"
                unpublish-label="{{$unpublishLabel}}"
                feature-label="{{$featureLabel}}"
                unfeature-label="{{$unfeatureLabel}}"
                restore-label="{{$restoreLabel}}"
                delete-label="{{$deleteLabel}}"
                draggable="{{ $reorder ? 'true' : 'false' }}"
                bulkeditable="{{ $bulkEdit ? 'true' : 'false' }}"
                empty-message={{$emptyMessage}}>
            </a17-datatable>
        @endif

        @if($create)
            <a17-modal-create
                ref="editionModal"
                form-create="{{ $storeUrl }}"
                v-on:reload="reloadDatas"
                @if ($customPublishedLabel ?? false) published-label="{{ $customPublishedLabel }}" @endif
                @if ($customDraftLabel ?? false) draft-label="{{ $customDraftLabel }}" @endif
                add-new-text="{{$addNewLabel}}"
                update-text="{{$updateLabel}}"
                error-message="{{$failedSubmissionLabel}}"
                error-variant="{{$addingErrorVariantLabel}}"
                create-label="{{$createLabel}}"
                create-add-another-label="{{$createAddAnotherLabel}}"
            >
                <a17-langmanager></a17-langmanager>
                @partialView(($moduleName ?? null), 'create', ['renderForModal' => true])
            </a17-modal-create>
        @endif

        <a17-dialog ref="warningDeleteRow" modal-title="{{$deleteModalTitle}}" confirm-label="{{$deleteLabel}}"
                    cancel-label="{{$cancelLabel}}">
            <p class="modal--tiny-title"><strong>{{$toTrash}}</strong></p>
            <p>{{$toTrashInfo}}</p>
        </a17-dialog>
    </div>
@stop

@section('initialStore')
    window.CMS_URLS = {
    index: @if(isset($indexUrl)) '{{ $indexUrl }}' @else window.location.href.split('?')[0] @endif,
    publish: '{{ $publishUrl }}',
    bulkPublish: '{{ $bulkPublishUrl }}',
    restore: '{{ $restoreUrl }}',
    bulkRestore: '{{ $bulkRestoreUrl }}',
    reorder: '{{ $reorderUrl }}',
    feature: '{{ $featureUrl }}',
    bulkFeature: '{{ $bulkFeatureUrl }}',
    bulkDelete: '{{ $bulkDeleteUrl }}'
    }

    window.STORE.form = {
    fields: []
    }

    window.STORE.datatable = {
    data: {!! json_encode($tableData) !!},
    columns: {!! json_encode($tableColumns) !!},
    navigation: {!! json_encode($tableMainFilters) !!},
    filter: { status: '{{ $filters['status'] ?? $defaultFilterSlug ?? 'all' }}' },
    page: {{ request('page') ?? 1 }},
    maxPage: {{ $maxPage ?? 1 }},
    defaultMaxPage: {{ $defaultMaxPage ?? 1 }},
    offset: {{ request('offset') ?? $offset ?? 60 }},
    defaultOffset: {{ $defaultOffset ?? 60 }},
    sortKey: '{{ $reorder ? (request('sortKey') ?? '') : (request('sortKey') ?? '') }}',
    sortDir: '{{ request('sortDir') ?? 'asc' }}',
    baseUrl: '{{ rtrim(config('app.url'), '/') . '/' }}',
    localStorageKey: '{{ isset($currentUser) ? $currentUser->id : 0 }}__{{ $moduleName ?? Route::currentRouteName() }}'
    }

    @if ($create && ($openCreate ?? false))
        window.openCreate = {!! json_encode($openCreate) !!}
    @endif
@stop

@push('extra_js')
    <script src="{{ mix('/assets/admin/js/manifest.js') }}"></script>
    <script src="{{ mix('/assets/admin/js/vendor.js') }}"></script>
    <script src="{{ mix('/assets/admin/js/main-listing.js') }}"></script>
@endpush


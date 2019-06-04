@php
    $type = $type ?? 'image';
    $max = $max ?? 1;
    $required = $required ?? false;
    $note = $note ?? '';
    $withAddInfo = $withAddInfo ?? true;
    $withVideoUrl = $withVideoUrl ?? true;
    $withCaption = $withCaption ?? true;
    $btnLabel = $btnLabel ?? "Attach images";
    $itemLabel = $itemLabel ?? "image";
@endphp

<a17-inputframe label="{{ $label }}" name="medias.{{ $name }}" @if ($required) :required="true" @endif>
    @if($max > 1 || $max == 0)
        <a17-slideshow
            @include('twill::partials.form.utils._field_name')
            item-label="{{$itemLabel}}"
            btn-label="{{$btnLabel}}"
            type="{{ $type }}"
            :max="{{ $max }}"
            crop-context="{{ $name }}"
            @if ($required) :required="true" @endif
            @if (!$withAddInfo) :with-add-info="false" @endif
            @if (!$withVideoUrl) :with-video-url="false" @endif
            @if (!$withCaption) :with-caption="false" @endif
        >{{ $note }}</a17-slideshow>
    @else
        <a17-mediafield
            @include('twill::partials.form.utils._field_name')
            btn-label="{{$btnLabel}}"
            type="{{ $type }}"
            crop-context="{{ $name }}"
            @if ($required) :required="true" @endif
            @if (!$withAddInfo) :with-add-info="false" @endif
            @if (!$withVideoUrl) :with-video-url="false" @endif
            @if (!$withCaption) :with-caption="false" @endif
        >{{ $note }}</a17-mediafield>
    @endif
</a17-inputframe>

@unless($renderForBlocks)
    @push('vuexStore')
        @if (isset($form_fields['medias']) && isset($form_fields['medias'][$name]))
            window.STORE.medias.selected["{{ $name }}"] = {!! json_encode($form_fields['medias'][$name]) !!}
        @endif
    @endpush
@endunless

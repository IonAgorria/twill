@php
    $type = $type ?? 'file';
    $max = $max ?? 1;
    $note = $note ?? 'Add' . ($max > 1 ? " up to $max files" : ' one file');
    $itemLabel = $itemLabel ?? strtolower($label);
    $addLabelValue = $addLabelValue ?? 'Add';
@endphp

<a17-locale
    type="a17-filefield"
    :attributes="{
        type: '{{$type}}',
        label: '{{ $label }}',
        itemLabel: '{{ $itemLabel }}',
        addLabelValue: '{{$addLabelValue}}',
        @include('twill::partials.form.utils._field_name', ['asAttributes' => true])
        note: '{{ $note }}',
        max: {{ $max }},
        }"
>
</a17-locale>

@unless($renderForBlocks)
    @push('vuexStore')
        @foreach(getLocales() as $locale)
            @if (isset($form_fields['files']) && isset($form_fields['files'][$locale][$name]))
                window.STORE.medias.selected["{{ $name }}[{{ $locale }}]"] = {!! json_encode($form_fields['files'][$locale][$name]) !!}
            @endif
        @endforeach
    @endpush
@endunless



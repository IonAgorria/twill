@formField('input', [
    'name' => $titleFormKey ?? 'title',
    'label' => ucfirst(__('fields.'.$titleFormKey) == 'fields.'.$titleFormKey ? null : __('fields.'.$titleFormKey) ?? 'title'),
    'translated' => $translateTitle ?? false,
    'required' => true,
    'onChange' => 'formatPermalink'
])

@if ($permalink ?? true)
    @formField('input', [
        'name' => 'slug',
        'label' => 'Permalink',
        'translated' => true,
        'ref' => 'permalink',
        'prefix' => $permalinkPrefix ?? ''
    ])
@endif

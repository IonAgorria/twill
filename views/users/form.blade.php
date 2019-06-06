@extends('twill::layouts.form', [
    'contentFieldsetLabel' => __('users.usersettings') == 'users.usersettings' ? 'User settings' : __('users.usersettings'),
    'editModalTitle' =>  __('users.editusername') == 'users.editusername' ?  'Edit user name': __('users.editusername'),
    'reloadOnSuccess' => true
])

@php
    $isSuperAdmin = isset($item->role) ? $item->role === 'SUPERADMIN' : false;
    $roleLabel = __('users.role') == 'users.role' ? 'Role' : __('users.role');
    $rolePlaceHolder = __('users.selectrole') == 'users.selectrole' ? 'Select a role' : __('users.selectrole');
    $profileImgLabel = __('users.profileimg') == 'users.profileimg' ? 'Profile image' : __('users.profileimg');
    $titleLabel = __('users.title') == 'users.title' ? 'Title' : __('users.title');
    $descriptionLabel = __('users.description') == 'users.description' ? 'User description' : __('users.description');
    $twoFactorLabel = __('users.twofactor') == 'users.twofactor' ? '2-factor authentication' : __('users.twofactor');
    $googleAuthLabel = __('users.googleauth') == 'users.googleauth' ? 'Please scan this QR code with a Google Authenticator compatible application and enter your one time password below before submitting. See a list of compatible applications ' : __('users.googleauth');
    $hereLabel = __('users.here') == 'users.here' ? ' here' : __('users.here');
    $oneTimePassLabel = __('users.onetimepass') == 'users.onetimepass' ? 'One time password' : __('users.onetimepass');
    $twoFADisableLabel = __('users.twofactordisable') == 'users.twofactordisable' ? 'Enter your one time password to disable the 2-factor authentication' : __('users.twofactordisable');
    $updateDisabledLabel = __('users.updatedisabled') == 'users.updatedisabled' ? 'Update disabled user' : __('users.updatedisabled');
    $updateDisabledCloseLabel = __('users.updatedisabledclose') == 'users.updatedisabledclose' ? 'Update disabled and close' : __('users.updatedisabledclose');
    $updateDisabledCreateLabel = __('users.updatedisablednew') == 'users.updatedisablednew' ? 'Update disabled user and create new' : __('users.updatedisablednew');
    $cancelLabel = __('navigation.cancel') == 'navigation.cancel' ? 'Cancel' : __('navigation.cancel');
    $enableUserLabel = __('users.enableuser') == 'users.enableuser' ? 'Enable user' : __('users.enableuser');
    $enableUserCloseLabel = __('users.enableclose') == 'users.enableclose' ? 'Enable user and close' : __('users.enableclose');
    $enableUserCreateLabel = __('users.enablecreate') == 'users.enablecreate' ? 'Enable user and create new' : __('users.enablecreate');
    $updateLabel = __('navigation.update') == 'navigation.update' ? 'Update' : __('navigation.update');
    $updateCloseLabel = __('users.updateclose') == 'users.updateclose' ? 'Update and close' : __('users.updateclose');
    $updateNewLabel = __('users.updatecreate') == 'users.updatecreate' ? 'Update and create new' : __('users.updatecreate');
@endphp

@section('contentFields')
    @formField('input', [
    'name' => 'email',
    'label' => 'Email'
    ])

    @can('edit-user-role')
        @if(!$isSuperAdmin && ($item->id !== $currentUser->id))
            @formField('select', [
            'name' => "role",
            'label' => $roleLabel,
            'options' => $roleList,
            'placeholder' => $rolePlaceHolder
            ])
        @endif
    @endcan

    @if(config('twill.enabled.users-image'))
        @formField('medias', [
        'btnLabel' => __('fields.attachoneimg'),
        'name' => 'profile',
        'label' => $profileImgLabel
        ])
    @endif
    @if(config('twill.enabled.users-description'))
        @formField('input', [
        'name' => 'title',
        'label' => $titleLabel,
        'maxlength' => 250
        ])
        @formField('input', [
        'name' => 'description',
        'rows' => 4,
        'type' => 'textarea',
        'label' => $descriptionLabel
        ])
    @endif
    @if($with2faSettings ?? false)
        @formField('checkbox', [
        'name' => 'google_2fa_enabled',
        'label' => $twoFactorLabel,
        ])

        @unless($item->google_2fa_enabled ?? false)
            @component('twill::partials.form.utils._connected_fields', [
                'fieldName' => 'google_2fa_enabled',
                'fieldValues' => true,
            ])
                <img style="display: block; margin-left: auto; margin-right: auto;" src="{{ $qrCode }}">
                <div class="f--regular f--note" style="margin: 20px 0;">{{$googleAuthLabel}}<a
                        href="https://github.com/antonioribeiro/google2fa#google-authenticator-apps" target="_blank"
                        rel="noopener">{{$hereLabel}}</a>.
                </div>
                @formField('input', [
                'name' => 'verify-code',
                'label' => $oneTimePassLabel,
                ])
            @endcomponent
        @else
            @component('twill::partials.form.utils._connected_fields', [
                'fieldName' => 'google_2fa_enabled',
                'fieldValues' => false,
            ])
                @formField('input', [
                'name' => 'verify-code',
                'label' => $oneTimePassLabel,
                'note' => $twoFADisableLabel
                ])
            @endcomponent
        @endunless
    @endif
@stop

@push('vuexStore')
    window.STORE.publication.submitOptions = {
        draft: [
            {
            name: 'save',
            text: '{{$updateDisabledLabel}}'
            },
            {
            name: 'save-close',
            text: '{{$updateDisabledCloseLabel}}'
            },
            {
            name: 'save-new',
            text: '{{$updateDisabledCreateLabel}}'
            },
            {
            name: 'cancel',
            text: '{{$cancelLabel}}'
            }
        ],
        live: [
            {
            name: 'publish',
            text: '{{$enableUserLabel}}'
            },
            {
            name: 'publish-close',
            text: '{{$enableUserCloseLabel}}'
            },
            {
            name: 'publish-new',
            text: '{{$enableUserCreateLabel}}'
            },
            {
            name: 'cancel',
            text: '{{$cancelLabel}}'
            }
        ],
        update: [
            {
            name: 'update',
            text: '{{$updateLabel}}'
            },
            {
            name: 'update-close',
            text: '{{$updateCloseLabel}}'
            },
            {
            name: 'update-new',
            text: '{{$updateNewLabel}}'
            },
            {
            name: 'cancel',
            text: '{{$cancelLabel}}'
            }
        ]
    }
    @if ($item->id == $currentUser->id)
        window.STORE.publication.withPublicationToggle = false
    @endif
@endpush

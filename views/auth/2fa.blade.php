@extends('twill::auth.layout', [
    'route' => route('admin.login-2fa'),
    'screenTitle' => __('auth.verifylogin')=='auth.verifylogin'?'Verify login':__('auth.verifylogin')
])

@section('form')
    <fieldset class="login__fieldset">
        <label class="login__label"
               for="verify-code">{{__('auth.onetimepass')=='auth.onetimepass'?'One-time password':__('auth.onetimepass')}}</label>
        <input type="number" name="verify-code" class="login__input" required autofocus tabindex="1"/>
    </fieldset>
    <input class="login__button" type="submit" value="Login" tabindex="3">
@stop

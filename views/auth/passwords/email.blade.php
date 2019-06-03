@extends('twill::auth.layout', [
    'route' => route('admin.password.reset.email'),
    'screenTitle' => __('auth.resetpassword')=='auth.resetpassword'?'Reset password':__('auth.resetpassword')
])

@section('form')
    <fieldset class="login__fieldset">
        <label class="login__label" for="email">Email</label>
        <input type="email" name="email" id="email" class="login__input" required autofocus />
    </fieldset>

    <input class="login__button" type="submit" value="{{__('auth.sendlink')=='auth.sendlink'?'Send password reset link':__('auth.sendlink')}}">
@stop

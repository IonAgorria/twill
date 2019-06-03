@php
    $passwordText = isset($welcome) && $welcome
        ? __('auth.choosepassword')=='auth.choosepassword'?'Choose password':__('auth.choosepassword')
     : __('auth.resetpassword')=='auth.resetpassword'?'Reset password':__('auth.resetpassword');
@endphp

@extends('twill::auth.layout', [
    'route' => route('admin.password.reset'),
    'screenTitle' => $passwordText
])

@section('form')
    <fieldset class="login__fieldset">
        <label class="login__label" for="email">Email</label>
        <input type="email" name="email" id="email" class="login__input" required autofocus value="{{ $email ?? '' }}"/>
    </fieldset>

    <fieldset class="login__fieldset">
        <label class="login__label"
               for="password">{{__('auth.password')=='auth.password'?'Password':__('auth.password')}}</label>
        <input type="password" name="password" id="password" class="login__input" required/>
    </fieldset>

    <fieldset class="login__fieldset">
        <label class="login__label"
               for="password_confirmation">{{__('auth.confirmpassword')=='auth.confirmpassword'?'Confirm password':__('auth.confirmpassword')}}</label>
        <input type="password" name="password_confirmation" id="password_confirmation" class="login__input" required/>
    </fieldset>

    <input type="hidden" name="token" value="{{ $token }}">

    <input class="login__button" type="submit" value="{{ $passwordText }}">
@stop

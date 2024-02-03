@extends('layout')

@section('content')
    <h1>{{ trans('Registration') }}</h1>
    <form method="post" action="{{ route('auth.registration') }}">
        @csrf
        <label for="email">{{ trans('E-Mail') }}:</label>
        <input type="email" id="email" name="email" placeholder="{{ trans('E-Mail') }}"
               required="required" autocomplete="email" autofocus="autofocus">
        <br>
        <label for="new-password">{{ trans('Password') }}:</label>
        <input type="password" id="password" name="password" placeholder="{{ trans('Password') }}"
               required="required" autocomplete="new-password">
        <br>
        <label for="password_confirmed">{{ trans('Repeat password') }}:</label>
        <input type="password" id="password_confirmed" name="password_confirmed" placeholder="{{ trans('Repeat password') }}"
               required="required" autocomplete="new-password">
        <br>
        <button type="submit">{{ trans('Register') }}</button>
    </form>
@endsection

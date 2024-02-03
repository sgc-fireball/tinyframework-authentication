@extends('layout')

@section('content')
    <form method="post" action="{{ route('auth.login', ['redirect' => $redirect]) }}">
        @csrf
        <label for="email">E-Mail:</label>
        <input type="email" id="email" name="email" placeholder="E-Mail" autocomplete="email" autofocus="autofocus">
        <br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" placeholder="Password" autocomplete="current-password">
        <br>
        <button type="submit">{{ trans('Login') }}</button>
        <br>
        <a href="{{ route('auth.password-forgotten') }}">{{ trans('Password forgotten') }}</a> <br>
        <a href="{{ route('auth.registration') }}">{{ trans('Register') }}</a>
    </form>
@endsection

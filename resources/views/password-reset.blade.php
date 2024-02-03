@extends('layout')

@section('content')
    <form method="post" action="{{ route('auth.password-reset', ['code' => $code]) }}">
        @csrf
        <label for="new-password">Password:</label>
        <input type="password" id="new-password" name="password" placeholder="Password" autocomplete="new-password">
        <br>
        <label for="password_confirmed">Repeat:</label>
        <input type="password" id="password_confirmed" name="password_confirmed" placeholder="Password"
               autocomplete="new-password">
        <br>
        <button type="submit">{{ trans('Reset') }}</button>
    </form>
@endsection

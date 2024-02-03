@extends('auth@email.layout_html')

@section('content')
    <a href="{{ url(route('auth.password-reset', ['code' => $user->password_reset_key])) }}">
        Password reset
    </a>
    <br>
    with code:
    {{ $user->password_reset_key }}
@endsection

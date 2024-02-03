@extends('auth@email.layout_text')

@section('content')
    Password reset:
    {{ url(route('auth.password-reset', ['code' => $user->password_reset_key])) }}
    with code:
    {{ $user->password_reset_key }}
@endsection

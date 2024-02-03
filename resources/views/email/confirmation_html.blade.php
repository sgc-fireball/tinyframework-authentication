@extends('auth@email.layout_html')

@section('content')
    <a href="{{ url(route('auth.verification', ['code' => $user->verification_key])) }}">
        Verify
    </a>
    <br>
    with code:
    {{ $user->verification_key }}
@endsection

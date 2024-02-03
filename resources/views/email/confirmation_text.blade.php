@extends('auth@email.layout_text')

@section('content')
    Verify:
    {{ url(route('auth.verification', ['code' => $user->verification_key])) }}
    with code:
    {{ $user->verification_key }}
@endsection

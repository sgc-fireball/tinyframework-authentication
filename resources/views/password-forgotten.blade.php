@extends('layout')

@section('content')
    <form method="post" action="{{ route('auth.password-forgotten') }}">
        @csrf
        <label for="email">{{ trans('E-Mail') }}:</label>
        <input type="email" id="email" name="email" placeholder="{{ trans('E-Mail') }}" autocomplete="email" autofocus="autofocus">
        <br>
        <button type="submit">{{ trans('Set') }}</button>
    </form>
@endsection

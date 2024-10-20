@extends('layout')

@section('content')
    <form method="post" action="{{ route('auth.password-reset', ['code' => $code]) }}">
        @csrf
        <label for="new-password">{{ trans('Password') }}:</label>
        <input type="password" id="new-password" name="password" placeholder="{{ trans('Password') }}" autocomplete="new-password" class="@class(['error' => error('password')])">
        @if (error('password'))
            <ul class="errors">
                @foreach (error('password') as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif
        <br>
        <label for="password_confirmed">Repeat:</label>
        <input type="password" id="password_confirmed" name="password_confirmed" placeholder="{{ trans('Password') }}"
               autocomplete="new-password" class="@class(['error' => error('password_confirmed')])">
        @if (error('password_confirmed'))
            <ul class="errors">
                @foreach (error('password_confirmed') as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif
        <br>
        <button type="submit">{{ trans('Reset') }}</button>
    </form>
@endsection

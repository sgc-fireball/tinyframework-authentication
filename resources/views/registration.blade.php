@extends('layout')

@section('content')
    <h1>{{ trans('Registration') }}</h1>
    <form method="post" action="{{ route('auth.registration') }}">
        @csrf
        <label for="email">{{ trans('E-Mail') }}:</label>
        <input type="email" id="email" name="email" placeholder="E-Mail" autocomplete="email" autofocus="autofocus"
               required="required" value="{{ old('email') }}" class="@class(['error' => error('email')])">
        @if (error('email'))
            <ul class="errors">
                @foreach (error('email') as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif
        <br>
        <label for="new-password">{{ trans('Password') }}:</label>
        <input type="password" id="password" name="password" placeholder="{{ trans('Password') }}"
               required="required" autocomplete="new-password" class="@class(['error' => error('password')])">
        @if (error('password'))
            <ul class="errors">
                @foreach (error('password') as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif
        <br>
        <label for="password_confirmed">{{ trans('Repeat password') }}:</label>
        <input type="password" id="password_confirmed" name="password_confirmed" placeholder="{{ trans('Repeat password') }}"
               required="required" autocomplete="new-password" class="@class(['error' => error('password_confirmed')])">
        @if (error('password_confirmed'))
            <ul class="errors">
                @foreach (error('password_confirmed') as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif
        <br>
        <button type="submit">{{ trans('Register') }}</button>
    </form>
@endsection

@extends('layout')

@section('content')
    <form method="post" action="{{ route('auth.login', ['redirect' => $redirect]) }}">
        @csrf
        <label for="email">{{ trans('E-Mail') }}:</label>
        <input type="email" id="email" name="email" placeholder="{{ trans('E-Mail') }}" autocomplete="email" autofocus="autofocus"
               required="required" value="{{ old('email') }}" class="@class(['error' => error('email')])">
        @if (error('email'))
            <ul class="errors">
                @foreach (error('email') as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif
        <br>
        <label for="password">{{ trans('Password') }}:</label>
        <input type="password" id="password" name="password" placeholder="{{ trans('Password') }}" autocomplete="current-password" required="required" class="@class(['error' => error('password')])">
        <br>
        <button type="submit">{{ trans('Login') }}</button>
        <br>
        <a href="{{ route('auth.password-forgotten') }}">{{ trans('Password forgotten') }}</a> <br>
        <a href="{{ route('auth.registration') }}">{{ trans('Register') }}</a>
    </form>
@endsection

@extends('layout')

@section('content')
    <form method="post" action="{{ route('auth.password-forgotten') }}">
        @csrf
        <label for="email">{{ trans('E-Mail') }}:</label>
        <input type="email" id="email" name="email" placeholder="{{ trans('E-Mail') }}" autocomplete="email"
               autofocus="autofocus"
               required="required" value="{{ old('email') }}" class="@class(['error' => error('email')])">
        @if (error('email'))
            <ul class="errors">
                @foreach (error('email') as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif
        <br>
        <button type="submit">{{ trans('Ask for a new Password') }}</button>
    </form>
@endsection

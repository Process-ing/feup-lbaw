@extends('layouts.auth')
@section('title') {{'Register | ProGram'}} @endsection
@section('content')
    <main class="grid grid-cols-4 items-center">
        @include('auth.register-form')
    </main>
@endsection

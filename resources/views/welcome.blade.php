@extends('layouts.app')
@section('nav')
    @include('partials.nav')
@endsection


@section('sghome')
    @include('partials.sghome')
@endsection

{{-- @section('search')
    @include('partials.tracking')
@endsection --}}

@section('content')
    <div class="max-w-full p-4  bg-blue-300">

        @include('services.card')

    </div>
@endsection

@section('footer')
    <div class="mt-8">
        @include('partials.footer')
    </div>
@endsection

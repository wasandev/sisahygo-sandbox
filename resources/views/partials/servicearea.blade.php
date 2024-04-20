@extends('layouts.app')
@section('nav')
    @include('partials.nav')
@endsection


@section('sghome')
    @include('partials.sghome')
@endsection



@section('content')
    <div class=" max-w-full  justify-center flex bg-white mb-8">
        <div class=" w-full px-2 py-2 m-2  bg-blue-300 rounded-lg">
            <div class="mt-2 px-2 py-1   align-middle">

                <p class="text-2xl">พื้นที่บริการขนส่งสินค้าของสี่สหายขนส่ง</p>

            </div>
            @livewire('service-area')

        </div>


    </div>
@endsection
@section('footer')
    <div class="mt-16">
        @include('partials.footer')
    </div>
@endsection

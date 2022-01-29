@extends('layouts.app')
@section('nav')
    @include('partials.nav')
@endsection
{{--
@section('search')
    @include('partials.tracking')
@endsection --}}

@section('sghome')
    @include('partials.sghome')
@endsection

@section('content')
<div id="app" class="max-w-full " >

    <div class="flex mx-4">

        <div class="w-full mx-auto">



                 <div class="p-4 m-4 max-w-full mx-auto text-center leading-tight bg-red-500 rounded-lg shadow-xl" >

                        <h1 class="mt-4 text-2xl text-gray-100 font-extrabold xl:text-3xl leading-tight">
                            SISAHY TRANSPORT
                        </h1>
                        <p class=" sm:block text-gray-100 text-2xl font-extrabold xl:text-2xl ">
                            บริษัท สี่สหายขนส่ง(1988) จำกัด
                        </p>
                        <p class="mt-2 leading-relaxed text-2xl font-extrabold  text-gray-100  antialiased ">
                            บริการรับ-ส่งสินค้า
                        </p>
                        <p></p>
                        <h1 class="mb-4 text-2xl text-gray-100 font-extrabold xl:text-3xl leading-tight">
                            CALL CENTER : 02 096 2444
                        </h1>

                </div>
                <div>
                    @include('services.card')

                </div>


        </div>


    </div>


</div>
@endsection

@section('footer')
    @include('partials.footer')

@endsection

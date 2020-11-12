@extends('layouts.app')
@section('nav')
    @include('partials.nav')
@endsection

@section('search')
    @include('partials.tracking')
@endsection

@section('mstorehome')
    @include('partials.sghome')
@endsection

@section('content')
<div id="app" class="max-w-full " >

    <div class="flex mx-4">

        <div class="w-full mx-auto  lg:w-2/3">


                <div>
                    @include('services.card')

                </div>

        </div>
        {{-- //right-side --}}

            <div class="hidden  lg:block lg:w-1/3  ">


            @include('pages.cardsidebar',[
                'showimage' => 0
            ])

            </div>

    </div>


</div>
@endsection

@section('footer')
    @include('partials.footer')

@endsection

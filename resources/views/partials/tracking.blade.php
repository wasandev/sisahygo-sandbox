@extends('layouts.app')
@section('nav')
    @include('partials.nav')
@endsection


@section('sghome')
    @include('partials.sghome')
@endsection



@section('content')
    <div class="max-w-full  justify-center flex bg-white mb-8">
        <div class=" w-full px-2 py-2 m-2  bg-blue-300 rounded-lg">
            <div class="mt-2 px-2 py-1   align-middle">

                <p class="text-3xl">ติดตามสินค้าสี่สหายขนส่ง</p>
                <p class="text-base text-white font-bold">ดูเลขที่ตรวจสอบสถานะหลังข้อความ "Ref ID:" จากเอกสารใบรับส่งสินค้า
                </p>
            </div>
            @livewire('order-tracking')

        </div>
        {{-- <div class="py-2 m-2">
            <button class="bg-gray-700 hover:bg-blue-500 text-white font-bold  rounded  items-baseline p-2 my-4">

                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                </svg>

            </button>

        </div> --}}

    </div>
@endsection
@section('footer')
    <div class="mt-16">
        @include('partials.footer')
    </div>
@endsection

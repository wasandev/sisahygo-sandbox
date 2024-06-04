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
                <p class="text-2xl">ติดตามสินค้าสี่สหายขนส่ง</p>
                <p class="text-base">ดูเลขที่ตรวจสอบสถานะหลังข้อความ "Ref ID:" จากเอกสารใบรับส่งสินค้า
                </p>
                <p class=" text-sm font-bold text-red-500 ">**สามารถติดตามสินค้าได้ 30 วันย้อนหลัง**
                </p>
            </div>
            @livewire('order-tracking')

        </div>


    </div>
@endsection
@section('footer')
    <div class="mt-16">
        @include('partials.footer')
    </div>
@endsection

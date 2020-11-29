@extends('layouts.app')


@section('content')
<div id="app" class="max-w-full " >

    <div class="flex mx-4">
        <div class="col-sm-4">
            <form method="get" action="/">
                <label>waybill</label>
                <select name="event_edit" class="form-control form-control-lg">
                    @foreach($waybillOptions as $waybill)
                        {{$waybill->name}}
                        {{-- <option value="{{$waybill->id}}">{{$waybill->waybill_no }}- {{$waybill->car->car_regist}}</option> --}}
                    @endforeach
                </select>
            </form>
        </div>


    </div>


</div>
@endsection


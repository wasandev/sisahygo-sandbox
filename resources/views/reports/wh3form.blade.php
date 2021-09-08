@extends('layouts.pdfdoc')



@section('content')
    {{ $form_name }}
    <iframe src="{{  url('storage/documents/'.$form_name) }}" type="application/pdf" style="width:600px; height:800px;">
    </iframe>
@endsection


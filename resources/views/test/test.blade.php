@extends('layouts.app')


@section('content')
<div id="app" class="max-w-md mx-auto " >

    <div>
       <template>

        <!-- ตำบล/แขวง -->
         <addressinput-subdistrict v-model="subdistrict" />
       </template>
       <template>
         <!-- อำเภอ/เขต -->
         <addressinput-district v-model="district" />
         </template>
         <template>
        <!-- จังหวัด -->
        <addressinput-province v-model="province" />
        </template>
         <!-- รหัสไปรษณีย์ -->
         <template>
         <addressinput-zipcode v-model="zipcode" />
        </template>
    </div>




</div>
@endsection


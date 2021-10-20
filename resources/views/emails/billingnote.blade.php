@component('mail::message')

เรียน {{$name}}

เอกสารประกอบการวางบิลตามไฟล์ที่แนบมา,

ขอขอบคุณ,<br>
{{ config('app.name') }}
@endcomponent

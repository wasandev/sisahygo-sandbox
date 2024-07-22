<div class="space-y-4">
    <div class="p-2">

        <form wire:submit.prevent="trackingOrder">
            <input type="number" wire:keydown.enter="trackingOrder" wire:model.defer="tracking"
                placeholder="ป้อนเลขที่ติดตามสินค้า Ref ID"
                class="w-10/12 duration-100 ease-in-out focus:outline-none focus:shadow-md border border-transparent focus:bg-gray-100  placeholder-gray-600 rounded-lg bg-gray-50 p-2  appearance-none leading-normal ">
            <button class="bg-blue-600 hover:bg-blue-400 text-white font-bold  rounded  items-baseline p-2 my-4"
                type="submit" wire:click="trackingOrder">
                ติดตามสินค้า
            </button>
        </form>

    </div>

    <div wire:loading>กำลังตรวจสอบข้อมูล...</div>
    <div wire:loading.remove></div>
    <div class="p-2">
        @if ($tracking == '')
            <div class="text-red-500 text-base">
                ป้อนเลขที่ติดตามสินค้า เพื่อติดตามสถานะการขนส่งสินค้า
            </div>
        @else
            @if ($order_statuses->isNotEmpty())
                <div class=" text-base font-semibold p-2">
                    เลขที่ใบรับส่งสินค้า : {{ $order_statuses->first()->order_header->order_header_no }} - Tracking
                    ID/Ref ID :
                    {{ $order_statuses->first()->order_header_id }}
                    {{-- สาขาปลายทาง : {{ $order_statuses->first()->order_header->branch_rec_id->branch->name }}
                    โทร: {{ $order_statuses->first()->order_header->branch_rec_id->branch->phoneno }} --}}
                </div>
                <table class="border-collapse table-fixed w-full text-base  rounded-md">
                    <thead>
                        <tr class="bg-gray-200">
                            <th
                                class="border-b dark:border-slate-600 font-medium p-4 pl-8 pt-4 pb-3 text-slate-800 text-left">
                                วันที่:เวลา</th>
                            <th
                                class="border-b dark:border-slate-600 font-medium p-4 pt-4 pb-3 text-slate-800 text-left">
                                สถานะการขนส่ง</th>
                            <th
                                class="border-b dark:border-slate-600 font-medium p-4 pr-8 pt-4 pb-3 text-slate-800 text-left">
                                ข้อมูลเพิ่มเติม</th>
                        </tr>
                    </thead>

                    @foreach ($order_statuses as $order_status)
                        @if ($order_status->status == 'confirmed')
                            @php
                                $icon = '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
  <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
</svg> ';
                                $status = 'สาขาต้นทางรับสินค้าไว้แล้ว';
                            @endphp
                        @elseif ($order_status->status == 'loaded')
                            @php
                                $icon = '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
  <path stroke-linecap="round" stroke-linejoin="round" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12" />
</svg>';
                                $status = 'สินค้าจัดขึ้นรถบรรทุกแล้ว';
                            @endphp
                        @elseif ($order_status->status == 'in transit')
                            @php
                                $icon = '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
  <path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z" />
  <path d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7a1 1 0 00-1 1v6.05A2.5 2.5 0 0115.95 16H17a1 1 0 001-1v-5a1 1 0 00-.293-.707l-2-2A1 1 0 0015 7h-1z" />
</svg>';
                                $status = 'สินค้าอยู่ระหว่างขนส่งไปสาขา';
                            @endphp
                        @elseif ($order_status->status == 'arrival')
                            @php
                                $icon = '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
  <path stroke-linecap="round" stroke-linejoin="round" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" />
</svg>';
                                $status = 'สินค้าถึงสาขาปลายทาง';
                            @endphp
                        @elseif ($order_status->status == 'branch warehouse')
                            @php
                                $icon = '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
  <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
</svg>';
                                $status = 'สินค้าอยู่คลังสาขารอการจัดส่ง';
                            @endphp
                        @elseif ($order_status->status == 'delivery')
                            @php
                                $icon = '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
  <path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
  <path stroke-linecap="round" stroke-linejoin="round" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
</svg>';
                                $status = 'สินค้าอยู่ระหว่างการจัดส่งจากสาขาไปถึงผู้รับ';
                            @endphp
                        @elseif ($order_status->status == 'completed')
                            @php
                                $icon = '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
  <path stroke-linecap="round" stroke-linejoin="round" d="M7 11.5V14m0-2.5v-6a1.5 1.5 0 113 0m-3 6a1.5 1.5 0 00-3 0v2a7.5 7.5 0 0015 0v-5a1.5 1.5 0 00-3 0m-6-3V11m0-5.5v-1a1.5 1.5 0 013 0v1m0 0V11m0-5.5a1.5 1.5 0 013 0v3m0 0V11" />
</svg>';
                                $status = 'สินค้าจัดส่งถึงผู้รับปลายทางแล้ว';
                            @endphp
                        @elseif ($order_status->status == 'cancel')
                            @php
                                $status = 'ยกเลิกรายการแล้ว';
                            @endphp
                        @else
                            @php
                                $status = 'สินค้ามีปัญหาการจัดส่ง';
                            @endphp
                        @endif
                        <tbody class="bg-white dark:bg-slate-800">

                            <tr>
                                <td
                                    class="border-b border-slate-100 dark:border-slate-700 p-4 pl-8 text-slate-500 dark:text-slate-400">

                                    {{ date('d/m/Y - H:i', strtotime($order_status->created_at)) }}
                                </td>
                                <td
                                    class=" border-b border-slate-100 dark:border-slate-700 p-4 text-slate-500 dark:text-slate-400">
                                    {!! $icon !!} {{ $status }}
                                </td>

                                <td
                                    class="border-b border-slate-100 dark:border-slate-700 p-4 pr-8 text-slate-500 dark:text-slate-400">
                                    @if ($order_status->status == 'confirmed')
                                        {{ $order_status->order_header->branch->name }} T.
                                        {{ $order_status->order_header->branch->phoneno }}
                                    @elseif ($order_status->status == 'loaded')
                                        {{ $order_status->order_header->branch->name }} รถบรรทุก :
                                        {{ $order_status->order_header->waybill->car->car_regist }}
                                    @elseif($order_status->status == 'in transit')
                                        รถบรรทุก :
                                        {{ $order_status->order_header->waybill->car->car_regist }}
                                    @elseif($order_status->status == 'arrival')
                                        {{ $order_status->order_header->to_branch->name }} T.
                                        {{ $order_status->order_header->to_branch->phoneno }}
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                    @endforeach
                    </tbody>
                </table>
            @else
                <div class="text-red-500 text-base">
                    ไม่พบข้อมูล กรุณาตรวจสอบเลขที่ตรวจสอบสถานะสินค้า (Ref ID) จากเอกสารใบรับส่งสินค้าอีกครั้ง
                </div>

            @endif
        @endif
    </div>
</div>

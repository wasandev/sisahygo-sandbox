<div class="space-y-4">
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" aria-labelledby="search"
        role="presentation" class="fill-current absolute  ml-3 mt-3 text-gray_200">
        <path fill-rule="nonzero"
            d="M14.32 12.906l5.387 5.387a1 1 0 0 1-1.414 1.414l-5.387-5.387a8 8 0 1 1 1.414-1.414zM8 14A6 6 0 1 0 8 2a6 6 0 0 0 0 12z">
        </path>
    </svg>
    <form wire:submit.prevent="trackingOrder">
        <input type="search" wire:keydown.enter="trackingOrder" wire:model.defer="tracking"
            placeholder="ติดตามสินค้า ป้อนเลขที่ติดตามสินค้า Ref ID"
            class="w-full duration-100 ease-in-out focus:outline-none focus:shadow-md border border-transparent focus:bg-gray-100  placeholder-gray-600 rounded-lg bg-gray-200 py-2  pl-10  appearance-none leading-normal ">
        <button class="bg-gray-700 hover:bg-blue-500 text-white font-bold  rounded  items-baseline p-2 my-4"
            type="submit" wire:click="trackingOrder">
            ค้นหา
        </button>
    </form>


    <div wire:loading>กำลังตรวจสอบข้อมูล...</div>
    <div wire:loading.remove></div>
    <div>
        @if ($tracking == '')
            <div class="text-red-500 text-base">
                ป้อนเลขที่ติดตามสินค้า เพื่อติดตามสถานะการขนส่งสินค้า
            </div>
        @else
            @if ($order_statuses->isNotEmpty())
                <table class="border-collapse table-fixed w-full text-sm">
                    <thead>
                        <tr>
                            <th
                                class="border-b dark:border-slate-600 font-medium p-4 pl-8 pt-4 pb-3 text-slate-400 dark:text-slate-200 text-left">
                                วัน:เวลา</th>
                            <th
                                class="border-b dark:border-slate-600 font-medium p-4 pt-4 pb-3 text-slate-400 dark:text-slate-200 text-left">
                                สถานะการขนส่ง</th>
                            <th
                                class="border-b dark:border-slate-600 font-medium p-4 pr-8 pt-4 pb-3 text-slate-400 dark:text-slate-200 text-left">
                                เพิ่มเติม</th>
                        </tr>
                    </thead>

                    @foreach ($order_statuses as $order_status)
                        @if ($order_status->status == 'confirmed')
                            @php
                                $status = 'สาขาต้นทางรับสินค้าไว้แล้ว';
                            @endphp
                        @elseif ($order_status->status == 'loaded')
                            @php
                                $status = 'สินค้าจัดขึ้นรถบรรทุกแล้ว';
                            @endphp
                        @elseif ($order_status->status == 'in transit')
                            @php
                                $status = 'สินค้าอยู่ระหว่างขนส่งไปสาขา';
                            @endphp
                        @elseif ($order_status->status == 'arrival')
                            @php
                                $status = 'สินค้าถึงสาขาปลายทาง';
                            @endphp
                        @elseif ($order_status->status == 'branch warehouse')
                            @php
                                $status = 'สินค้าอยู่คลังสาขารอการจัดส่ง';
                            @endphp
                        @elseif ($order_status->status == 'delivery')
                            @php
                                $status = 'สินค้าอยู่ระหว่างการจัดส่งจากสาขาไปถึงผู้รับ';
                            @endphp
                        @elseif ($order_status->status == 'completed')
                            @php
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

                                    {{ $order_status->created_at }}
                                </td>
                                <td
                                    class="border-b border-slate-100 dark:border-slate-700 p-4 text-slate-500 dark:text-slate-400">
                                    {{ $status }}
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
                    ไม่พบข้อมูล กรุณาตรวจสอบหมายเลขตรวจสอบสถานะสินค้า (Ref ID) จากเอกสารใบรับส่งสินค้าอีกครั้ง
                </div>

            @endif
        @endif
    </div>
</div>

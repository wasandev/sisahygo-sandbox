<div class="space-y-4">
    <div>

        <div>
            <div>
                <!-- Search box -->
                <input type="text"
                    class="w-full duration-100 ease-in-out focus:outline-none focus:shadow-md border border-transparent focus:bg-gray-100  placeholder-gray-600 rounded-lg bg-gray-200 py-2  pl-10  appearance-none leading-normal "
                    placeholder="ค้นหาอำเภอที่อยู่ในพื้นที่บริการของสี่สหายขนส่ง" wire:model="searchTerm">
                <div wire:loading>กำลังตรวจสอบข้อมูล...</div>
                <div wire:loading.remove></div>
                <!-- Paginated records -->
                <table class="border-collapse border border-slate-400 table-fixed w-full text-base mt-4 bg-gray-100">


                    <thead>
                        <tr>
                            <th class="border border-slate-300  font-medium p-4 pl-8 pt-4 pb-3 text-slate-400 dark:text-slate-200 text-left"
                                wire:click="sortOrder('branch_id')">สาขาปลายทาง {!! $sortLink !!}
                            </th>
                            <th class="border border-slate-300 font-medium p-4 pl-8 pt-4 pb-3 text-slate-400 dark:text-slate-200 text-left"
                                wire:click="sortOrder('province')">จังหวัด {!! $sortLink !!}</th>
                            <th class="border border-slate-300 font-medium p-4 pl-8 pt-4 pb-3 text-slate-400 dark:text-slate-200 text-left"
                                wire:click="sortOrder('district')">อำเภอ {!! $sortLink !!}</th>


                        </tr>
                    </thead>
                    <tbody>
                        @if ($branchareas->count())
                            @foreach ($branchareas as $brancharea)
                                <tr>
                                    <td class="border border-slate-300">{{ $brancharea->branch->name }}</td>
                                    <td class="border border-slate-300">{{ $brancharea->province }}</td>
                                    <td class="border border-slate-300">{{ $brancharea->district }}</td>

                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="3">อำเภอที่ค้นหาไม่อยู่ในพื้นที่บริการ</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            <div class="space-y-4 mt-2">
                <!-- Pagination navigation links -->
                {{ $branchareas->links() }}
            </div>
        </div>

    </div>
</div>

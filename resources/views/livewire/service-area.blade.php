<div class="space-y-4">
    <div>

        <div>
            <div>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                    aria-labelledby="search" role="presentation" class="fill-current absolute  ml-3 mt-3 text-gray_200">
                    <path fill-rule="nonzero"
                        d="M14.32 12.906l5.387 5.387a1 1 0 0 1-1.414 1.414l-5.387-5.387a8 8 0 1 1 1.414-1.414zM8 14A6 6 0 1 0 8 2a6 6 0 0 0 0 12z">
                    </path>
                </svg>
                <!-- Search box -->
                <input type="text"
                    class="w-full duration-100 ease-in-out focus:outline-none focus:shadow-md border border-transparent focus:bg-gray-100  placeholder-gray-600 rounded-lg bg-white py-2  pl-10  appearance-none leading-normal "
                    placeholder="ค้นหาอำเภอที่อยู่ในพื้นที่บริการของสี่สหายขนส่ง" wire:model="searchTerm">
                <div wire:loading>กำลังตรวจสอบข้อมูล...</div>
                <div wire:loading.remove></div>
                <!-- Paginated records -->
                <table
                    class="border-collapse border border-slate-400 table-fixed w-full text-base mt-4 p-4 bg-gray-100">


                    <thead>
                        <tr>
                            <th class="border border-slate-300  font-medium p-4 pl-8 pt-4 pb-3 text-slate-800 text-left"
                                wire:click="sortOrder('branch_id')">สาขาปลายทาง {!! $sortLink !!}
                            </th>
                            <th class="border border-slate-300 font-medium p-4 pl-8 pt-4 pb-3 text-slate-800 text-left"
                                wire:click="sortOrder('province')">จังหวัด {!! $sortLink !!}</th>
                            <th class="border border-slate-300 font-medium p-4 pl-8 pt-4 pb-3 text-slate-800 text-left"
                                wire:click="sortOrder('district')">อำเภอ {!! $sortLink !!}</th>


                        </tr>
                    </thead>
                    <tbody>
                        @if ($branchareas->count())
                            @foreach ($branchareas as $brancharea)
                                <tr>
                                    <td class="border border-slate-300 px-4 py-3">{{ $brancharea->branch->name }}</td>
                                    <td class="border border-slate-300 px-4 py-3">{{ $brancharea->province }}</td>
                                    <td class="border border-slate-300 px-4 py-3">{{ $brancharea->district }}</td>

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
            <div class="mt-2 items-stretch">
                <!-- Pagination navigation links -->
                {{ $branchareas->links() }}
            </div>
        </div>

    </div>
</div>

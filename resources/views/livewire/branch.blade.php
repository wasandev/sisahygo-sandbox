<div class="space-y-4">


    <div>
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" aria-labelledby="search"
            role="presentation" class="fill-current absolute  ml-3 mt-3 text-gray_200">
            <path fill-rule="nonzero"
                d="M14.32 12.906l5.387 5.387a1 1 0 0 1-1.414 1.414l-5.387-5.387a8 8 0 1 1 1.414-1.414zM8 14A6 6 0 1 0 8 2a6 6 0 0 0 0 12z">
            </path>
        </svg>
        <!-- Search box -->
        <input type="text"
            class="w-full duration-100 ease-in-out focus:outline-none focus:shadow-md border border-transparent focus:bg-gray-100  placeholder-gray-600 rounded-lg bg-gray-200 py-2  pl-10  appearance-none leading-normal "
            placeholder="ค้นหาสาขา" wire:model="searchTerm">
        <div wire:loading>กำลังค้นหาข้อมูล...</div>
        <div wire:loading.remove></div>
        <!-- Paginated records -->
        <div class="overflow-x-auto">
            <table class="border-collapse border border-slate-400 table-auto w-full  mt-4 bg-gray-100">


                <thead>
                    <tr>
                        <th class="border border-slate-300  font-medium p-4 pl-8 pt-4 pb-3 text-slate-400 dark:text-slate-200 text-left"
                            wire:click="sortOrder('name')">ชื่อสาขา {!! $sortLink !!}
                        </th>
                        <th class="border border-slate-300 font-medium p-4 pl-8 pt-4 pb-3 text-slate-400 dark:text-slate-200 text-left"
                            wire:click="sortOrder('address')">ที่อยู่ {!! $sortLink !!}</th>
                        <th class="border border-slate-300 font-medium p-4 pl-8 pt-4 pb-3 text-slate-400 dark:text-slate-200 text-left"
                            wire:click="sortOrder('district')">อำเภอ {!! $sortLink !!}</th>
                        <th class="border border-slate-300 font-medium p-4 pl-8 pt-4 pb-3 text-slate-400 dark:text-slate-200 text-left"
                            wire:click="sortOrder('province')">จังหวัด {!! $sortLink !!}</th>
                        <th class="border border-slate-300 font-medium p-4 pl-8 pt-4 pb-3 text-slate-400 dark:text-slate-200 text-left"
                            wire:click="sortOrder('phoneno')">โทรศัพท์ {!! $sortLink !!}</th>

                    </tr>
                </thead>
                <tbody>
                    @if ($branchs->count())
                        @foreach ($branchs as $branch)
                            <tr>
                                <td class="border border-slate-300">{{ $branch->name }}</td>
                                <td class="border border-slate-300">{{ $branch->address }}</td>
                                <td class="border border-slate-300">{{ $branch->district }}</td>
                                <td class="border border-slate-300">{{ $branch->province }}</td>
                                <td class="border border-slate-300">{{ $branch->phoneno }}</td>

                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="3">ไม่พบข้อมูล</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
    <div class="space-y-4 mt-2">
        <!-- Pagination navigation links -->
        {{ $branchs->links() }}
    </div>


</div>

<div>
    <section class="bg-gray-50 p-3 sm:p-5">
        <div class="mx-auto max-w-full px-2 ">
            <!-- Start coding here -->
            <div class="bg-white relative shadow-md sm:rounded-lg overflow-hidden">
                <div
                    class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4">
                    <div class="w-full md:w-1/2">
                        <form class="flex items-center">
                            <label for="simple-search" class="sr-only">ค้นหาราคาค่าขนส่ง</label>
                            <div class="relative w-full">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <svg aria-hidden="true" class="w-5 h-5 text-gray-600" fill="currentColor"
                                        viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd"
                                            d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <input wire:model.live="searchPrice" type="text" id="simple-search"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                    placeholder="ค้นหาสินค้า..." required="">
                            </div>
                        </form>
                    </div>
                    <div class="w-64 inline-flex">

                        <select wire:model.live="searchBrancharea"
                            class="block p-4 w-full text-base text-gray-500 bg-transparent border-1 border-b-2 border-gray-200 appearance-none focus:outline-none focus:ring-0 focus:border-gray-200 peer">
                            <option value=""> เลือกอำเภอปลายทาง </option>
                            @foreach ($branchareas as $district => $brancharea)
                                <option value="{{ $district }}"> {{ $brancharea }} </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500 ">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-4 py-3">สินค้า</th>
                                <th scope="col" class="px-4 py-3">ไปอำเภอ</th>
                                <th scope="col" class="px-4 py-3">จังหวัด</th>
                                <th scope="col" class="px-4 py-3">ค่าขนส่ง</th>
                                <th scope="col" class="px-4 py-3">ต่อหนวย</th>

                            </tr>
                        </thead>
                        <tbody>
                            @forelse($serviceprices as $serviceprice)
                                <tr class="border-b">
                                    <td class="px-4 py-3">
                                        {{ $serviceprice->product->name }} </td>
                                    <td class="px-4 py-3">{{ $serviceprice->district }}</td>
                                    <td class="px-4 py-3">{{ $serviceprice->province }}</td>
                                    <td class="px-4 py-3"> {{ number_format($serviceprice->price, 2, '.', ',') }} </td>
                                    <td class="px-4 py-3"> {{ $serviceprice->unit->name }} </td>

                                </tr>
                            @empty
                                <tr>
                                    <td class="px-6 py-4 text-sm" colspan="3">
                                        ไม่พบค่าขนส่งสินค้าที่ต้องการค้นหา
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="grid h-16 place-items-center m-4">
                    <div class="p-4 border">
                        {{ $serviceprices->links() }}
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

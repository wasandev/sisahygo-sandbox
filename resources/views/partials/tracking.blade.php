
<div  class="max-w-full mx-auto bg-blue-700  pt-16 pb-4">
    <form action="/tracking" method="POST" role="search">
        @csrf
        <div class="max-w-lg mx-auto ">
            <div class="flex w-full p-4 md:p-4 text-2xl">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" aria-labelledby="search" role="presentation" class="fill-current absolute  ml-3 mt-3 text-gray_200"><path fill-rule="nonzero" d="M14.32 12.906l5.387 5.387a1 1 0 0 1-1.414 1.414l-5.387-5.387a8 8 0 1 1 1.414-1.414zM8 14A6 6 0 1 0 8 2a6 6 0 0 0 0 12z"></path></svg>
                <input name="vendor-search" type="search" placeholder="ติดตามสินค้า ป้อนเลข Tracking no" class="transition-colors duration-100 ease-in-out focus:outline-none focus:shadow-md border border-transparent focus:bg-white  placeholder-gray-600 rounded-full bg-gray-100 py-2 pr-4 pl-10 block w-full appearance-none leading-normal ">

            </div>
        </div>

</div>

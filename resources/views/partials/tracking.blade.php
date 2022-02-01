
<div  class="w-full bg-gray-800 items-center">
    <form action="/tracking" method="POST" role="search">
        @csrf
        <div class="mx-auto flex">
            <div class="p-4 text-xl block ">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" aria-labelledby="search" role="presentation" class="fill-current absolute  ml-3 mt-3 text-gray_200"><path fill-rule="nonzero" d="M14.32 12.906l5.387 5.387a1 1 0 0 1-1.414 1.414l-5.387-5.387a8 8 0 1 1 1.414-1.414zM8 14A6 6 0 1 0 8 2a6 6 0 0 0 0 12z"></path></svg>
                <input name="vendor-search" type="search" placeholder="ติดตามสินค้า ป้อนเลข Tracking no"
                class="w-full transition-colors duration-100 ease-in-out focus:outline-none focus:shadow-md border border-transparent focus:bg-white  placeholder-gray-600 rounded-lg bg-gray-100 py-2  pl-10  appearance-none leading-normal ">

            </div>
            <div class="p-4 text-lg block ">
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 pr-2 mr-4 rounded ">
                    QRCODE
                </button>

            </div>
        </div>
    </form>
</div>

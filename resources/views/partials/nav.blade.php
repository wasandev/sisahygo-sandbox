<div class=" bg-blue-600  lg:flex justify-between px-2 py-2 shadow fixed top-0 inset-x-0 z-50 ">

        <div class="flex items-center justify-between px-2 ">
            <div class=" flex text-left flex-no-shrink mr-0">
                <a class="flex text-base  no-underline hover:text-mstore hover:no-underline" href="/">
                    @include('partials.logo')
                </a>

            </div>
             <div class=" block lg:hidden ">
                <form action="/tracking" method="POST" role="search">
                    @csrf
                         <div class="max-w-full flex w-full px-4 text-xl">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" aria-labelledby="search" role="presentation" class="fill-current absolute  ml-3 mt-3 text-gray_200"><path fill-rule="nonzero" d="M14.32 12.906l5.387 5.387a1 1 0 0 1-1.414 1.414l-5.387-5.387a8 8 0 1 1 1.414-1.414zM8 14A6 6 0 1 0 8 2a6 6 0 0 0 0 12z"></path></svg>
                            <input name="tracking" type="search" placeholder="ติดตามสินค้า ป้อนเลข Tracking no"
                            class="transition-colors duration-100 ease-in-out focus:outline-none focus:shadow-md border border-transparent focus:bg-white  placeholder-gray-600 rounded-full bg-white py-2 pr-4 pl-10 block  appearance-none leading-normal ">

                        </div>

                </form>
            </div>
            <div class="block lg:hidden">
                <button class="navbar-burger block  text-gray-100 hover:text-red-500 focus:text-red-500 focus:outline-none">
                    <svg class="h-6 w-6 fill-current absolute" viewBox="0 0 24 24">
                    <path  fill-rule="evenodd" d="M4 5h16a1 1 0 0 1 0 2H4a1 1 0 1 1 0-2zm0 6h16a1 1 0 0 1 0 2H4a1 1 0 0 1 0-2zm0 6h16a1 1 0 0 1 0 2H4a1 1 0 0 1 0-2z"/>
                    </svg>
                    <span class="ml-6">MENU</span>
                </button>
            </div>

        </div>
        <div class="lg:block hidden">

            <form action="/tracking" method="POST" role="search">
                @csrf
                <div class="px-2 text-xl">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" aria-labelledby="search" role="presentation" class="fill-current absolute  ml-3 mt-3 text-gray_200"><path fill-rule="nonzero" d="M14.32 12.906l5.387 5.387a1 1 0 0 1-1.414 1.414l-5.387-5.387a8 8 0 1 1 1.414-1.414zM8 14A6 6 0 1 0 8 2a6 6 0 0 0 0 12z"></path></svg>
                    <input name="tracking" type="search" placeholder="ติดตามสินค้า ป้อนเลข Tracking no"
                    class="w-full transition-colors duration-100 ease-in-out focus:outline-none focus:shadow-md border border-transparent focus:bg-white  placeholder-gray-600 rounded-full bg-white px-2 py-2  pl-10  appearance-none leading-normal ">

                </div>

            </form>

        </div>

        <div id="main-nav" class="lg:block  hidden text-md font-semibold text-left m-2">


            <div class="lg:flex lg:justify-between items-center  ">

                <a href="/" class="mx-2 block px-3 py-1 text-gray-100 mt-2 lg:mt-0 rounded-md hover:bg-red-500 hover:text-gray-100">

                    <span>หน้าหลัก</span>
                </a>


                <a href="/tracking" class="mx-2 block px-3 py-1 text-gray-100 mt-2 lg:mt-0 rounded-md  hover:bg-red-500 hover:text-gray-100  ">

                    <span>ติตตามสินค้า</span>
                </a>
                <a href="/service-price" class="mx-2 block px-3 py-1 text-gray-100 mt-2 lg:mt-0 rounded-md  hover:bg-red-500 hover:text-gray-100  ">

                    <span>สอบถามราคา</span>
                </a>
                <a href="/service-area" class="mx-2 block px-3 py-1 text-gray-100 mt-2 lg:mt-0 rounded-md  hover:bg-red-500 hover:text-gray-100  ">

                    <span>พื้นที่บริการ</span>
                </a>
            </div>

        </div>
</div>



<nav class=" bg-blue-600 lg:flex justify-between   shadow fixed top-0 inset-x-0 z-50 ">

    <div class="flex items-center justify-between ">
        <div class=" flex text-left flex-no-shrink mr-0">
            <a class="flex text-base  no-underline hover:text-mstore hover:no-underline" href="/">
                @include('partials.logo')
            </a>

        </div>

        <div class="block lg:hidden">
            <button class="navbar-burger block  text-gray-100 hover:text-red-500 focus:text-red-300 focus:outline-none"
                aria-controls="main-nav">

                <svg class="h-6 w-6 fill-current absolute" viewBox="0 0 24 24">
                    <path fill-rule="evenodd"
                        d="M4 5h16a1 1 0 0 1 0 2H4a1 1 0 1 1 0-2zm0 6h16a1 1 0 0 1 0 2H4a1 1 0 0 1 0-2zm0 6h16a1 1 0 0 1 0 2H4a1 1 0 0 1 0-2z" />
                </svg>
                <span class="ml-6 mr-2">MENU</span>
            </button>
        </div>

    </div>

    <div id="main-nav" class="lg:block  hidden text-md  text-left m-2">


        <div class="lg:flex lg:justify-between items-center  ">
            <div class="flex p-4">
                <a href="tel:020962444"
                    class="inline-flex mx-2  p-2 text-red-500 bg-gray-100 mt-2 lg:mt-0 rounded-md hover:bg-white  hover:text-black">

                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                    </svg> 020962444
                </a>
            </div>

            <a href="/"
                class="mx-2 block p-2 text-gray-100 mt-4 lg:mt-0 rounded-md hover:bg-red-500  hover:text-gray-100">

                <span>หน้าหลัก</span>
            </a>


            <a href="/order-tracking"
                class="mx-2 block p-2 text-gray-100 mt-4 lg:mt-0 rounded-md  hover:bg-red-500 hover:text-gray-100  ">

                <span>ติดตามสินค้า</span>
            </a>
            <a href="/service-price"
                class="mx-2 block p-2 text-gray-100 mt-4 lg:mt-0 rounded-md  hover:bg-red-500 hover:text-gray-100  ">

                <span>ค่าขนส่งสินค้า</span>
            </a>
            <a href="/service-area"
                class="mx-2 block p-2 text-gray-100 mt-4 lg:mt-0 rounded-md  hover:bg-red-500 hover:text-gray-100  ">

                <span>พื้นที่บริการ</span>
            </a>
        </div>

    </div>
</nav>

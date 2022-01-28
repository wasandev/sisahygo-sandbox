<div class=" bg-white  lg:flex lg:justify-between lg:items-center lg:px-4 lg:py-2 shadow fixed top-0 inset-x-0 z-50 ">

        <div class="flex items-center justify-between px-4 py-3 lg:p-0">
            <div class="flex text-left flex-no-shrink mr-0">
                <a class="flex text-base  no-underline hover:text-mstore hover:no-underline" href="/">
                    @include('partials.logo')
                </a>

            </div>

            <div class="block lg:hidden ">
                <button class="navbar-burger block  text-blue-500 hover:text-red-500 focus:text-gray-800 focus:outline-none">
                    <svg class="h-6 w-6 fill-current absolute" viewBox="0 0 24 24">
                    <path  fill-rule="evenodd" d="M4 5h16a1 1 0 0 1 0 2H4a1 1 0 1 1 0-2zm0 6h16a1 1 0 0 1 0 2H4a1 1 0 0 1 0-2zm0 6h16a1 1 0 0 1 0 2H4a1 1 0 0 1 0-2z"/>
                    </svg>
                    <span class="ml-6">MENU</span>
                </button>
            </div>

        </div>



        <div id="main-nav" class="lg:block lg:w-auto hidden text-base  text-left m-2">
            <div class="lg:flex lg:justify-between items-center  ">
                <a href="/" class="mx-2 block px-3 py-1 text-gray-800 mt-2 lg:mt-0 rounded-md hover:bg-red-700 hover:text-gray-100">

                    <span>Home</span>
                </a>


                <a href="/tracking" class="mx-2 block px-3 py-1 text-gray-800 mt-2 lg:mt-0 rounded-md  hover:bg-red-700 hover:text-gray-100  ">

                    <span>ติดตามสถานะการขนส่ง</span>
                </a>
                <a href="/service-price" class="mx-2 block px-3 py-1 text-gray-800 mt-2 lg:mt-0 rounded-md  hover:bg-red-500 hover:text-gray-100  ">

                    <span>สอบถามราคา</span>
                </a>
                <a href="/service-area" class="mx-2 block px-3 py-1 text-gray-800 mt-2 lg:mt-0 rounded-md  hover:bg-red-500 hover:text-gray-100  ">

                    <span>พื้นที่บริการ</span>
                </a>
            </div>

        </div>
</div>



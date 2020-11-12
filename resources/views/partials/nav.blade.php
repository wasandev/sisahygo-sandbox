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



        <div id="main-nav" class="lg:block lg:w-auto hidden text-base  text-left">
            <div class="lg:flex lg:justify-between items-center  ">
                <a href="/" class="mx-2 block px-3 py-1 text-gray-800 mt-2 lg:mt-0 rounded-full hover:bg-blue-500 hover:text-gray-100">

                    <span>Home</span>
                </a>
                <a href="/news" class="mx-2 block px-3 py-1 text-gray-800 mt-2 lg:mt-0 rounded-full hover:bg-blue-500 hover:text-gray-100  ">

                    <span>News</span>
                </a>
                <a href="/services" class="mx-2 block px-3 py-1 text-gray-800 mt-2 lg:mt-0 rounded-full hover:bg-blue-500 hover:text-gray-100 ">

                    <span>Our Service</span>
                </a>

                <a href="/tracking" class="mx-2 block px-3 py-1 text-gray-800 mt-2 lg:mt-0 rounded-full  hover:bg-blue-500 hover:text-gray-100  ">

                    <span>Tracking</span>
                </a>


                @auth

                    <div class="relative group hidden lg:block lg:ml-6">

                        <div  class="flex items-center cursor-pointer  text-gray-800  group-hover: border-gray  hover:text-blue">
                            <img class="h-8 w-8 border border-gray-100 rounded-full object-cover" src="{{ Storage::url(Auth::user()->avatar) }}"
                                alt="">
                            <span class="ml-3 mt-1 px-3 py-1 rounded-full text-red-500 hover:bg-red-500 hover:text-gray-100 border border-red-500">{{ Auth::user()->name }}</span>
                        </div>

                        <div class="w-48 absolute right-0 mt-0 py-1 bg-gray-200  shadow-xl invisible group-hover:visible">
                            <a href="#" onclick="addToHomeScreen()" class="no-underline px-3 py-1 block text-grey-900 hover:text-gray-100 hover:bg-blue-500">
                                <span class="ml-6">เพิ่มไปหน้าจอหลัก</span>
                            </a>
                            <a href="/app" target="_blank"
                                class="no-underline px-3 py-1 block text-grey-900  hover:text-gray-100 hover:bg-blue-500">

                                <span class="ml-6">เข้าสู่ระบบงาน</span>
                            </a>


                            <hr class="border-t mx-2 border-gray-300">

                            <a href="{{ route('logout') }}"
                                class=" no-underline px-3 py-1 block text-gray-900 hover:text-blue-500" onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();">
                                  <svg xmlns="http://www.w3.org/2000/svg" class="fill-current absolute mr-2"  width="20" height="20" viewBox="0 0 24 24"><path d="M10 7H2v6h8v5l8-8-8-8v5z"/></svg>

                                  <span class="ml-6 text-red-500">{{ __('Logout') }}</span>

                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                    </div>


                @else
                    <a href="/app" target="_blank"
                        class="mx-2 block px-3 py-1 mt-2 lg:mt-0   mb-2 lg:mb-0 text-blue-600 border border-gray-200 rounded-full hover:bg-blue-500 hover:text-gray-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="fill-current absolute mr-2"  width="20" height="20" viewBox="0 0 24 24"><path d="M5 5a5 5 0 0 1 10 0v2A5 5 0 0 1 5 7V5zM0 16.68A19.9 19.9 0 0 1 10 14c3.64 0 7.06.97 10 2.68V20H0v-3.32z"/></svg>

                        <span class="ml-6">{{ __('Login') }}</span>
                    </a>
                @endauth

                </div>
            </div>
            @auth
                <div class="px-3 py-1 lg:hidden bg-gray-300 mt-2">
                    <div  class="w-full text-center">
                        <img class="h-12 w-12 border-2 border-gray-600 rounded-full object-cover mx-auto" src="{{ Storage::url(Auth::user()->avatar) }}" alt="">
                        <span class="text-base text-gray-800">{{ Auth::user()->name }}</span>
                    </div>
                    <div class="mt-4">


                            <hr class="border-t mx-2 border-gray-300">
                            <a href="/home" class="px-2 py-1 mt-2 block text-gray-800  hover:bg-blue-500 hover:text-gray-100 lg:mt-0 lg:ml-2">
                                {{-- <svg xmlns="http://www.w3.org/2000/svg" class="fill-current absolute mr-2"  width="20" height="20" viewBox="0 0 20 20"><path d="M18 9.87V20H2V9.87a4.25 4.25 0 0 0 3-.38V14h10V9.5a4.26 4.26 0 0 0 3 .37zM3 0h4l-.67 6.03A3.43 3.43 0 0 1 3 9C1.34 9 .42 7.73.95 6.15L3 0zm5 0h4l.7 6.3c.17 1.5-.91 2.7-2.42 2.7h-.56A2.38 2.38 0 0 1 7.3 6.3L8 0zm5 0h4l2.05 6.15C19.58 7.73 18.65 9 17 9a3.42 3.42 0 0 1-3.33-2.97L13 0z"/></svg> --}}
                                <span class="ml-6">ระบบงาน SISAHYGO</span>
                            </a>

                        <hr class="border-t mx-2 border-gray-300">

                        <a href="{{ route('logout') }}" class="px-2 py-1 mt-2 block text-gray-800 rounded hover:bg-red-500 hover:text-gray-100 lg:mt-0 lg:ml-2"
                            onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();">
                             <svg xmlns="http://www.w3.org/2000/svg" class="fill-current absolute mr-2"  width="20" height="20" viewBox="0 0 20 20"><path d="M10 7H2v6h8v5l8-8-8-8v5z"/></svg>

                             <span class="ml-6 ">{{ __('Logout') }}</span>
                        </a>
                        <form id="logout-form1" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </div>
            @endauth
        </div>
</div>



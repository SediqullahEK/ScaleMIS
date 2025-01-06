<nav dir="rtl"
    class="fixed top-0 left-0 z-10 w-full items-center justify-between bg-gradient-to-b from-orange-100 via-[#f1f3f5] to-[#e4e5f7] py-3 drop-shadow-md shadow-black/5 dark:bg-neutral-600 dark:shadow-black/10 lg:flex-wrap lg:justify-start lg:py-3 float-left  lg:w-[82%] ">
    <div class="flex w-full items-center justify-between px-3">
        <!-- User Profile -->
        <div class="relative mr-4" data-te-dropdown-ref>
            <!-- Second dropdown trigger -->
            <a class="hidden-arrow flex items-center whitespace-nowrap transition duration-150 ease-in-out motion-reduce:transition-none"
                href="#" id="dropdownMenuButton2" role="button" data-te-dropdown-toggle-ref aria-expanded="false">


                <img id="profile-photo"
                    src="{{ auth()->user()->profile_photo_path ? url('storage/' . auth()->user()->profile_photo_path) : url('storage/user_profiles/profileIcon.png') }}"
                    class="rounded-full" style="height: 40px; width: 40px" alt="" loading="lazy">


                @auth
                    <span class="px-2" id="full-name">
                        {{ auth()->user()->full_name }}
                    </span>
                @endauth

            </a>
            @if (auth()->user()->logged_in)
                <ul class="absolute left-auto right-0 z-[1000] float-left m-0 mt-1 hidden min-w-max list-none overflow-hidden rounded-lg border-none bg-white bg-clip-padding text-left text-base shadow-lg dark:bg-neutral-700 [&[data-te-dropdown-show]]:block"
                    aria-labelledby="dropdownMenuButton2" data-te-dropdown-menu-ref>
                    @role(1)
                        <li>
                            <a class="block w-full whitespace-nowrap bg-transparent px-4 py-2 text-sm font-normal text-neutral-700 hover:bg-neutral-100 active:text-neutral-800 active:no-underline disabled:pointer-events-none disabled:bg-transparent disabled:text-neutral-400 dark:text-neutral-200 dark:hover:bg-white/30"
                                href="{{ route('user_dashboard') }}" data-te-dropdown-item-ref>مدیریت کاربران</a>
                        </li>
                    @endrole
                    <li>
                        <a class="block w-full whitespace-nowrap bg-transparent px-4 py-2 text-sm font-normal text-neutral-700 hover:bg-neutral-100 active:text-neutral-800 active:no-underline disabled:pointer-events-none disabled:bg-transparent disabled:text-neutral-400 dark:text-neutral-200 dark:hover:bg-white/30"
                            href="{{ route('user_profile') }}" data-te-dropdown-item-ref>پروفایل</a>
                    </li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <input type="submit"
                                class="block w-full cursor-pointer whitespace-nowrap bg-transparent px-4 py-2 text-sm font-normal text-neutral-700 hover:bg-neutral-100 active:text-neutral-800 active:no-underline disabled:pointer-events-none disabled:bg-transparent disabled:text-neutral-400 dark:text-neutral-200 dark:hover:bg-white/30"
                                href="#" data-te-dropdown-item-ref value="خارج شدن از سیستم">
                        </form>

                    </li>
                </ul>
            @endif
        </div>


        <a href="#" class="logo">
            <img src="{{ asset('storage/system_image/main_logo.webp') }}" class="h-12" alt="Main Logo" />
        </a>

        <button
            class="block border-0 bg-transparent px-2 text-neutral-500 hover:no-underline focus:outline-none lg:hidden"
            type="button" data-te-collapse-init data-te-target="#navbarSupportedContent1"
            aria-controls="navbarSupportedContent1" aria-expanded="false" aria-label="Toggle navigation">
            <span>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-7 w-7">
                    <path fill-rule="evenodd"
                        d="M3 6.75A.75.75 0 013.75 6h16.5a.75.75 0 010 1.5H3.75A.75.75 0 013 6.75zM3 12a.75.75 0 01.75-.75h16.5a.75.75 0 010 1.5H3.75A.75.75 0 013 12zm0 5.25a.75.75 0 01.75-.75h16.5a.75.75 0 010 1.5H3.75a.75.75 0 01-.75-.75z"
                        clip-rule="evenodd" />
                </svg>
            </span>
        </button>

    </div>
    @if (auth()->user()->logged_in)
        <div id="navbarSupportedContent1"
            class="mt-3 hidden lg:hidden flex-col lg:w-full lg:justify-between lg:items-center lg:relative px-3">
            <div class="flex flex-col space-y-4">
                <a href="{{ route('high_scale_dashboard') }}"
                    class="bg-[#123853] text-white px-4 py-2 rounded hover:bg-[#c78903] {{ request()->routeIs('high_scale_dashboard') ? '!bg-[#c78903]' : '' }}">
                    داشبورد عمومی ترازو ها
                </a>
                <a href="{{ route('highscale.report') }}"
                    class="bg-[#123853] text-white px-4 py-2 rounded hover:bg-[#c78903] {{ request()->routeIs('highscale.report') ? '!bg-[#c78903]' : '' }}">
                    راپور ترازو ها بلند تناژ
                </a>
                <div x-data="{ open: false }" class="relative">
                    <a @click="open = !open"
                        class="bg-[#123853] text-white px-4 py-2 rounded hover:bg-[#c78903] flex items-center">
                        ترازو ها بر نقشه افغانستان
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 ml-2" :class="{ 'rotate-90': open }"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                    <div x-show="open" x-cloak class="absolute bg-[#123853] mt-2 rounded shadow-lg p-2">
                        <a href="{{ route('Dashboard.Afghanistan_map') }}"
                            class="block text-white py-1 px-3 hover:bg-[#c78903] {{ request()->routeIs('Dashboard.Afghanistan_map') ? 'bg-[#c78903]' : '' }}">
                            نقشه
                        </a>
                        @role(1)
                            <a href="{{ route('add_scale') }}"
                                class="block text-white py-1 px-3 hover:bg-[#c78903] {{ request()->routeIs('add_scale') ? 'bg-[#c78903]' : '' }}">
                                اضافه نمودن ترازو
                            </a>
                        @endrole
                    </div>
                </div>
            </div>
        </div>
    @endif
</nav>

@push('otherjs')
    <script>
        window.addEventListener('nameUpdated', event => {
            const name = document.getElementById('full-name');

            if (event.detail && event.detail[0] && event.detail[0].full_name) {
                name.innerHTML = event.detail[0].full_name;
            } else {
                console.error("name not found");
            }
        });
        window.addEventListener('profile-photo-updated', event => {
            const img = document.getElementById('profile-photo');

            if (event.detail && event.detail[0] && event.detail[0].src) {
                img.src = event.detail[0].src + '?' + new Date().getTime();
            } else {
                console.error("src not found in event.detail");
            }
        });
    </script>
@endpush

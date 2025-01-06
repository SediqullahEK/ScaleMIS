<div id="sidebar"
    class="hidden lg:block lg:w-[18%] bg-gradient-to-t from-[#1A2E3C] to-[#06243F] bg-[80%] shadow-md drop-shadow-lg md:top-0 md:right-0 md:bottom-0 md:fixed">
    <!-- Logo Section -->
    <div class="w-[180px] h-[180px] overflow-hidden rounded-full mx-auto mt-3">
        <img class="w-full h-full" src="{{ asset('storage/icons/logo.webp') }}" alt="Logo">
    </div>

    <!-- Sidebar Links -->
    <div id="sidebar-links" class="flex flex-col mt-2 w-full font-bold lg:text-xs text-sm " dir="rtl">
        <h1 class="p-2 mb-6 text-blue-100 mx-1 text-base">سیســـتم مدیریت ترازو های بلند تناژ</h1>
        @if (auth()->user()->logged_in)
            <div>
                <a href="{{ route('high_scale_dashboard') }}"
                    class="p-3 bg-[#123853] border-r mb-2 border-white cursor-pointer text-white flex justify-between rounded shadow mx-1 hover:bg-[#c78903] hover:text-white {{ request()->routeIs('high_scale_dashboard') ? '!bg-[#c78903] text-white' : '' }}">
                    <span class="text-[14px]">داشبورد عمومی ترازو ها</span>
                </a>
                <a href="{{ route('highscale.report') }}"
                    class="p-3 bg-[#123853] border-r border-white cursor-pointer mb-2 text-white flex justify-between rounded shadow mx-1 hover:bg-[#c78903] hover:text-white {{ request()->routeIs('highscale.report') ? '!bg-[#c78903] text-white' : '' }}">
                    <span class="text-[14px]">راپور ترازو های بلند تناژ</span>
                </a>

                <div x-data="{ open: {{ request()->routeIs('Dashboard.Afghanistan_map') || request()->routeIs('add_scale') ? 'true' : 'false' }} }">
                    <!-- Main Link -->
                    <a @click="open = !open"
                        class="p-3 mb-2 bg-[#123853] border-r border-white cursor-pointer text-white flex justify-between rounded shadow mx-1 hover:bg-[#c78903] hover:text-white {{ request()->routeIs('Dashboard.Afghanistan_map') || request()->routeIs('add_scale') ? '!bg-[#c78903] text-white' : '' }}">
                        <span class="text-[14px]">ترازو ها بر نقشه افغانستان</span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-5 h-5 transition-transform" :class="{ 'rotate-90': open }">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                        </svg>
                    </a>

                    <!-- Submenu -->
                    <div x-show="open" x-collapse class="ml-4 mt-2 space-y-2 mb-2">
                        <a href="{{ route('Dashboard.Afghanistan_map') }}"
                            class="block p-2 bg-[#123853] text-white cursor-pointer rounded hover:bg-[#c78903] hover:text-white mr-3 {{ request()->routeIs('Dashboard.Afghanistan_map') ? '!bg-[#c78903] text-white' : '' }}">
                            نقشه
                        </a>
                        @role(1)
                            <a href="{{ route('add_scale') }}"
                                class="block p-2 bg-[#123853] text-white cursor-pointer rounded hover:bg-[#c78903] hover:text-white mr-3 {{ request()->routeIs('add_scale') ? '!bg-[#c78903] text-white' : '' }}">
                                اضافه نمودن ترازو در نقشه
                            </a>
                        @endrole
                    </div>
                </div>
            </div>
        @endif

    </div>
</div>

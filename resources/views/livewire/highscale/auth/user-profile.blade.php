@push('othercss')
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
@endpush


<div class="bg-white w-full flex flex-col gap-5 px-3 md:px-16 lg:px-28 md:flex-row text-[#161931]" dir='rtl'>

    <div wire:loading wire:target="updateProfile, profile_image">
        <x-live-loader />
    </div>

    <aside class="py-4 w-full md:w-1/3 lg:w-1/4">
        <div class="p-4 text-sm border-l border-sky-100">
            <h2 class="pl-3 mb-4 text-2xl font-semibold">تنظیمات</h2>

            <a href="#" wire:click="toggleChangePassword(0)"
                class="flex items-center px-3 py-2.5 font-bold border rounded-full transition
           @if (!$changePassword) bg-sky-100 text-sky-800 border-sky-400 
           @else bg-white text-sky-900 hover:bg-sky-50 hover:text-sky-800 @endif">
                معلومات شخصی
            </a>

            <a href="#" wire:click="toggleChangePassword(1)"
                class="flex items-center px-3 py-2.5 font-bold border rounded-full transition
           @if ($changePassword) bg-sky-100 text-sky-800 border-sky-400 
           @else bg-white text-sky-900 hover:bg-sky-50 hover:text-sky-800 @endif">
                تغییر رمز کاربردی
            </a>
        </div>
    </aside>




    @if (!$changePassword)
        <main class="w-full min-h-screen py-1 md:w-2/3 lg:w-3/4 mx-auto">

            {{-- alerts section --}}
            <div wire:poll.100ms>
                @if (session()->has('message'))
                    <div x-data="{ show: @json(session()->has('message')) }" x-init="if (show) { setTimeout(() => { show = false }, 3500); }" x-show="show"
                        class="fixed top-16 left-1/2 transform -translate-x-1/2 bg-green-300 text-green-800 px-3 py-4 shadow-xl flex justify-between items-center rounded-lg w-auto">
                        <button @click="show = false"
                            class="text-gray-500 hover:text-gray-700 text-2xl ">&times;</button>
                        {{ session('message') }}
                        <svg class="h-5 w-5 ml-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </div>
                @endif

                @if (session()->has('error'))
                    <div x-data="{ show: @json(session()->has('error')) }" x-init="if (show) { setTimeout(() => { show = false }, 3500); }" x-show="show"
                        class="fixed top-16 left-1/2 transform -translate-x-1/2 bg-red-300 text-gray-800 px-3 py-4 shadow-xl flex justify-between items-center rounded-lg w-auto">
                        <button @click="show = false"
                            class="text-gray-500 hover:text-gray-700 text-2xl ">&times;</button>
                        {{ session('error') }}
                        <svg class="h-5 w-5 ml-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </div>
                @endif

            </div>
            <div x-data="{ isOpen: @entangle('isOpen') }">
                <!-- Modal Overlay -->
                <div x-show="isOpen" style="display: none;"
                    class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-50">
                    <!-- Modal Container -->
                    <div id="modal" x-show="isOpen" x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90"
                        class="bg-white p-6 rounded-lg shadow-xl max-w-sm w-full mx-auto">

                        <!-- Modal Header -->
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">
                            آیا مطمعن هستید که می‌خواهید عکس پروفایل را حذف کنید؟
                        </h2>

                        <!-- Buttons -->
                        <div class="flex justify-center gap-4 mt-6">
                            <button id="cancelButton" onclick="closeModal()"
                                class="bg-red-600 text-white px-5 py-2 rounded-md hover:bg-red-500 transition">
                                لغو
                            </button>
                            <button id="confirmButton"
                                class="bg-blue-600 text-white px-5 py-2 rounded-md hover:bg-blue-500 transition">
                                تایید
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-2 md:p-4">
                <div class="w-full px-6 pb-8 mt-8 sm:max-w-xl sm:rounded-lg">
                    <h2 class="pl-6 text-2xl font-bold sm:text-xl">معلومات شخصی</h2>
                    <div class="grid max-w-2xl mx-auto mt-8">

                        <div class="flex flex-col items-center space-y-6 sm:flex-row sm:items-start sm:space-x-8">
                            <!-- Profile Image -->
                            <img id="profile-photo" src="{{ url('storage/' . $profile) }}"
                                class="object-cover w-40 h-40 p-1 rounded-full shadow-lg border border-gray-200"
                                alt="Profile Image" loading="lazy">

                            <!-- Action Buttons -->
                            <div class="flex flex-col items-center space-y-4 mr-4">
                                <div class="flex space-x-8">
                                    <div class="flex flex-col items-center space-y-4 mr-4">
                                        <div class="flex space-x-8">
                                            <!-- Change Profile Photo -->
                                            <div class="flex flex-col items-center">
                                                <input type="file" wire:model="profile_image" id="file-upload"
                                                    accept="image/*" class="hidden">
                                                <label for="file-upload"
                                                    class="cursor-pointer flex items-center justify-center w-12 h-12 bg-blue-500 text-white rounded-full shadow-md hover:bg-blue-600 transition">
                                                    <i class="fas fa-edit"></i>
                                                </label>
                                                <span class="text-sm text-gray-500 mt-1 mx-4">تغییر عکس</span>
                                                @error('profile_image')
                                                    <p class="text-red-500 text-sm">{{ $message }}</p>
                                                @enderror
                                            </div>

                                            <!-- Delete Profile Photo -->
                                            <div class="flex flex-col items-center mr-4">
                                                <button type="button" onclick="openModal()"
                                                    class="cursor-pointer flex items-center justify-center w-12 h-12 bg-red-500 text-white rounded-full shadow-md hover:bg-red-600 transition">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                                <span class="text-sm text-gray-500 mt-1">حذف عکس</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>




                        <form wire:submit.prevent="updateProfile">
                            <div class="items-center mt-8 sm:mt-14 text-[#202142]">
                                <div
                                    class="flex flex-col items-center w-full mb-2 space-y-2 sm:flex-row sm:space-x-4 sm:space-y-0 sm:mb-6">
                                    <div class="w-full">
                                        <label for="full_name" class="block mb-2 text-sm font-medium text-sky-900">اسم
                                            مکمل</label>
                                        <input type="text" id="full_name" wire:model="full_name"
                                            class="bg-sky-50 border border-sky-300 text-sky-900 text-sm rounded-lg focus:ring-sky-500 focus:border-sky-500 block w-full p-2.5"
                                            placeholder="اسم مکمل تان را وارد کنید" required>
                                        @error('full_name')
                                            <p class="text-red-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div
                                    class="flex flex-col items-center w-full mb-2 space-y-2 sm:flex-row sm:space-x-4 sm:space-y-0 sm:mb-6">
                                    <div class="w-full">
                                        <label for="user_name" class="block mb-2 text-sm font-medium text-sky-900">نام
                                            کاربری</label>
                                        <input type="text" id="user_name" wire:model="user_name"
                                            class="bg-sky-50 border border-sky-300 text-sky-900 text-sm rounded-lg focus:ring-sky-500 focus:border-sky-500 block w-full p-2.5"
                                            placeholder="نام کاربری تان را وارد کنید" required>
                                        @error('user_name')
                                            <p class="text-red-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-2 sm:mb-6">
                                    <label for="email"
                                        class="block mb-2 text-sm font-medium text-sky-900">ایمیل</label>
                                    <input type="email" id="email" wire:model="email"
                                        class="bg-sky-50 border border-sky-300 text-sky-900 text-sm rounded-lg focus:ring-sky-500 focus:border-sky-500 block w-full p-2.5"
                                        placeholder="ایمیل تان را وارد کنید" required>
                                    @error('email')
                                        <p class="text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="flex justify-start">
                                    <button type="submit"
                                        class="text-white bg-sky-700 hover:bg-sky-800 focus:ring-4 focus:outline-none focus:ring-sky-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">
                                        ذخیره
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    @elseif($changePassword)
        <main class="w-full min-h-screen py-1 md:w-2/3 lg:w-3/4 ">
            <div class="p-2 md:p-4">
                <div class="w-full px-6 pb-8 mt-8 sm:max-w-xl sm:rounded-lg">
                    <livewire:highscale.auth.fl-change-password :embedded="true" />
                </div>
            </div>
        </main>
    @endif
</div>
@push('otherjs')
    <script>
        function openModal() {
            const confirmButton = document.getElementById("confirmButton");
            @this.isOpen = true;
            confirmButton.onclick = function() {

                @this.deleteProfileImage();
                closeModal();
            };
        }

        function closeModal() {
            @this.isOpen = false;
        }
    </script>
@endpush

<div>
    @if (session()->has('success'))
        <div class="flex items-center p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 border border-green-200 shadow-md"
            role="alert">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M18 10A8 8 0 11. . ."></path>
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @elseif (session()->has('error'))
        <div class="flex items-center p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 border border-red-200 shadow-md"
            role="alert">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M18 10A8 8 0 11. . ."></path>
            </svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif
    @if ($embedded)
        <div wire:loading wire:target="updatePassword">

            <x-live-loader />
        </div>
        <h2 class="text-2xl font-bold sm:text-xl text-start">تغییر رمز کاربری</h2>
        <form class="mt-8" wire:submit.prevent="updatePassword">
            <div class="grid max-w-3xl mx-auto gap-4">

                <!-- Current Password -->
                <div class="text-[#202142]">
                    <div class="mb-4">
                        <label for="current_password" class="block mb-2 text-sm font-medium text-sky-900">رمز
                            فعلی</label>
                        <input type="password" id="current_password" wire:model="current_password"
                            class="bg-sky-50 border border-sky-300 text-sky-900 text-sm rounded-lg focus:ring-sky-400 focus:border-sky-400 block w-full p-2.5"
                            placeholder="رمز فعلی تان را وارد کنید" required>
                        @error('current_password')
                            <p class="text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- New Password -->
                    <div class="mb-4">
                        <label for="new_password" class="block mb-2 text-sm font-medium text-sky-900">رمز
                            جدید</label>
                        <input type="password" id="new_password" wire:model="new_password"
                            class="bg-sky-50 border border-sky-300 text-sky-900 text-sm rounded-lg focus:ring-sky-400 focus:border-sky-400 block w-full p-2.5"
                            placeholder="رمز جدید تان را وارد کنید" required>
                        @error('new_password')
                            <p class="text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm New Password -->
                    <div class="mb-4">
                        <label for="new_password_confirmation" class="block mb-2 text-sm font-medium text-sky-900">تایید
                            رمز
                            جدید</label>
                        <input type="password" id="new_password_confirmation" wire:model="new_password_confirmation"
                            class="bg-sky-50 border border-sky-300 text-sky-900 text-sm rounded-lg focus:ring-sky-400 focus:border-sky-400 block w-full p-2.5"
                            placeholder="رمز جدید تان را دوباره وارد کنید" required>
                        @error('new_password_confirmation')
                            <p class="text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-start">
                        <button type="submit"
                            class="mt-4 text-white bg-sky-900 hover:bg-sky-800 focus:ring-4 focus:outline-none focus:ring-sky-300 font-medium rounded-lg text-md w-full md:w-auto px-5 py-2.5 text-center">
                            ذخیره
                        </button>
                    </div>


                </div>
            </div>
        </form>
    @else
        <div class="bg-white w-full flex items-center justify-center px-3 md:px-16 lg:px-28 text-[#161931]"
            dir='rtl'>

            <main class="w-full min-h-screen flex justify-center items-center py-1 md:w-2/3 lg:w-3/4">
                <div wire:loading wire:target="updatePassword">

                    <x-live-loader />
                </div>
                <div class="p-2 md:p-4 w-full">
                    <div class="w-full px-6 pb-8 sm:max-w-xl sm:rounded-lg mx-auto">
                        <h2 class="text-2xl font-bold sm:text-xl text-center">تغییر رمز کاربری</h2>
                        <form class="mt-8" wire:submit.prevent="updatePassword">
                            <div class="grid max-w-2xl mx-auto gap-4">

                                <!-- Current Password -->
                                <div class="text-[#202142]">
                                    <div class="mb-4">
                                        <label for="current_password"
                                            class="block mb-2 text-sm font-medium text-sky-900">رمز
                                            فعلی</label>
                                        <input type="password" id="current_password" wire:model="current_password"
                                            class="bg-sky-50 border border-sky-300 text-sky-900 text-sm rounded-lg focus:ring-sky-400 focus:border-sky-400 block w-full p-2.5"
                                            placeholder="رمز فعلی تان را وارد کنید" required>
                                        @error('current_password')
                                            <p class="text-red-500">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- New Password -->
                                    <div class="mb-4">
                                        <label for="new_password"
                                            class="block mb-2 text-sm font-medium text-sky-900">رمز
                                            جدید</label>
                                        <input type="password" id="new_password" wire:model="new_password"
                                            class="bg-sky-50 border border-sky-300 text-sky-900 text-sm rounded-lg focus:ring-sky-400 focus:border-sky-400 block w-full p-2.5"
                                            placeholder="رمز جدید تان را وارد کنید" required>
                                        @error('new_password')
                                            <p class="text-red-500">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Confirm New Password -->
                                    <div class="mb-4">
                                        <label for="new_password_confirmation"
                                            class="block mb-2 text-sm font-medium text-sky-900">تایید رمز جدید</label>
                                        <input type="password" id="new_password_confirmation"
                                            wire:model="new_password_confirmation"
                                            class="bg-sky-50 border border-sky-300 text-sky-900 text-sm rounded-lg focus:ring-sky-400 focus:border-sky-400 block w-full p-2.5"
                                            placeholder="رمز جدید تان را دوباره وارد کنید" required>
                                        @error('new_password_confirmation')
                                            <p class="text-red-500">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Submit Button -->
                                    <div class="flex justify-center">
                                        <button type="submit"
                                            class="text-white bg-sky-700 hover:bg-sky-800 focus:ring-4 focus:outline-none focus:ring-sky-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">
                                            ذخیره
                                        </button>
                                    </div>


                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </main>
        </div>
    @endif
</div>

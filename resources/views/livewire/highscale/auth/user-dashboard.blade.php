<div class="relative bg-white shadow-md drop-shadow-md dark:bg-gray-800 sm:rounded-lg">

    <div wire:loading wire:target="addUser , render">
        <x-live-loader />
    </div>
    <x-pages_comp.topheader pageTitle='مدیریت کاربران' />
    @role(1)
        <div class="flex justify-end mr-4">
            <button @click=" @this.call('resetForm'); @this.call('openForm',1)"
                class="bg-teal-600 text-white p-2 rounded flex items-center">
                <span class="text-2xl px-3 flex items-center">
                    <i class="fa fa-plus"></i>
                </span>
            </button>
        </div>

        <div x-data="{ isOpen: @entangle('isOpen') }" dir="rtl">
            <div x-show="isOpen" style="display: none;"
                class="fixed inset-0 z-50 flex items-start justify-center bg-gray-900 bg-opacity-50" @click.stop>

                <!-- Modal Structure -->
                <div x-show="isOpen" x-transition:enter="transition ease-out duration-500"
                    x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-90"
                    class="bg-white p-6 rounded-lg shadow-lg w-full max-w-3xl mt-12">

                    {{-- Modal Content --}}
                    <div>
                        {{-- Modal Header  --}}
                        <div class="flex justify-between items-center pb-4 border-b w-full max-w-3xl">
                            <h2 class="text-xl font-semibold">
                                {{ $isEditing ? 'ویرایش کاربر' : 'افزودن کاربر جدید' }}
                            </h2>
                            <button @click="isOpen = false; @this.call('resetForm');"
                                class="text-gray-500 hover:text-gray-700 text-4xl p-2">&times;</button>
                        </div>

                        {{-- User Registration Form --}}
                        <form wire:submit.prevent="{{ $isEditing ? 'updateUser' : 'addUser' }}"
                            class="grid lg:grid-cols-4 md:grid-cols-2 sm:grid-cols-1 gap-4">
                            <!-- Full Name -->
                            <input type="number" hidden wire:model.live='userId'>
                            <span class="col-span-2 text-right">
                                <label class="font-bold text-sm">نام مکمل</label>
                                <span class="text-red-700">*</span>
                                <input type="text" required wire:model.live="full_name"
                                    class="mt-1 peer block h-10 w-full bg-white border border-slate-300 rounded-md text-sm shadow-sm focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                                    autocomplete="off" dir="rtl">
                                @error('full_name')
                                    <p class="text-red-500">{{ $message }}</p>
                                @enderror
                            </span>

                            <!-- User Name -->
                            <span class="col-span-2 text-right">
                                <label class="font-bold text-sm">نام کاربری</label>
                                <span class="text-red-700">*</span>
                                <input type="text" required wire:model.live="user_name"
                                    class="mt-1 peer block h-10 w-full bg-white border border-slate-300 rounded-md text-sm shadow-sm focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                                    autocomplete="off" dir="rtl">
                                @error('user_name')
                                    <p class="text-red-500">{{ $message }}</p>
                                @enderror
                            </span>

                            <!-- Email -->
                            <span class="col-span-2 text-right">
                                <label class="font-bold text-sm">ایمیل</label>
                                <span class="text-red-700">*</span>
                                <input type="email" required wire:model.live="email"
                                    class="mt-1 peer block h-10 w-full bg-white border border-slate-300 rounded-md text-sm shadow-sm focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                                    autocomplete="off" dir="rtl">
                                @error('email')
                                    <p class="text-red-500">{{ $message }}</p>
                                @enderror
                            </span>



                            <span class="col-span-2 text-right">
                                <label class="font-bold text-sm">ولایت</label>
                                <span class="text-red-700">*</span>
                                <select required wire:model.live="province"
                                    class="mt-1 peer block h-10 w-full bg-blue border border-slate-300 rounded-md text-sm shadow-sm focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500">
                                    <option value="0" disabled hidden selected>ولایت کاربر را انتخاب کنید</option>
                                    @foreach ($provinces as $pr)
                                        <option value="{{ $pr->province_code }}">{{ $pr->name }}</option>
                                    @endforeach
                                </select>
                                @error('provinces')
                                    <p class="text-red-500">{{ $message }}</p>
                                @enderror
                            </span>
                            @if (!$isEditing)
                                <span class="col-span-2 text-right">
                                    <label class="font-bold text-sm">رمز عبور</label>
                                    <span class="text-red-700">*</span>
                                    <input type="password" required wire:model.live="password"
                                        class="mt-1 peer block h-10 w-full bg-white border border-slate-300 rounded-md text-sm shadow-sm focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                                        autocomplete="off" dir="rtl">
                                    @error('password')
                                        <p class="text-red-500">{{ $message }}</p>
                                    @enderror
                                </span>

                                <span class="col-span-2 text-right">
                                    <label class="font-bold text-sm">تایید رمز عبور</label>
                                    <span class="text-red-700">*</span>
                                    <input type="password" required wire:model.live="password_confirmation"
                                        class="mt-1 peer block h-10 w-full bg-white border border-slate-300 rounded-md text-sm shadow-sm focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                                        autocomplete="off" dir="rtl">
                                    @error('password_confirmation')
                                        <p class="text-red-500">{{ $message }}</p>
                                    @enderror
                                </span>
                            @endif
                            <span class="col-span-2 text-right">
                                <label class="font-bold text-sm">صلاحیت</label>
                                <span class="text-red-700">*</span>
                                <select required wire:model.live="position"
                                    class="mt-1 peer block h-10 w-full bg-blue border border-slate-300 rounded-md text-sm shadow-sm focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500">
                                    <option value="0" disabled hidden selected>صلاحیت کاربر را انتخاب کنید</option>
                                    @foreach ($positions as $ps)
                                        <option value="{{ $ps->id }}">{{ $ps->name }}</option>
                                    @endforeach
                                </select>
                                @error('position')
                                    <p class="text-red-500">{{ $message }}</p>
                                @enderror
                            </span>

                            <div class="flex ">
                                <span class="w-1/2 text-right">
                                    <label class="font-bold text-sm">عکس پروفایل</label>
                                    <input type="file" wire:model.live="profile_image" id="file-upload" accept="image/*"
                                        class="hidden" />
                                    <label for="file-upload"
                                        class="cursor-pointer mt-1 block h-10 w-full bg-white border border-slate-300 rounded-md text-sm shadow-sm flex items-center justify-center text-gray-700 hover:bg-gray-100 focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500">
                                        انتخاب عکس
                                    </label>
                                    @error('profile_image')
                                        <p class="text-red-500">{{ $message }}</p>
                                    @enderror
                                </span>

                                <!-- Image Preview -->
                                <span class="w-1/2 flex items-center justify-center ">
                                    <span wire:loading wire:target="profile_image"
                                        class="col-start-5 col-span-1 justify-self-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24">
                                            <rect width="6" height="14" x="1" y="4" fill="black">
                                                <animate id="svgSpinnersBarsFade0" fill="freeze" attributeName="opacity"
                                                    begin="0;svgSpinnersBarsFade1.end-0.175s" dur="0.525s"
                                                    values="1;0.2" />
                                            </rect>
                                            <rect width="6" height="14" x="9" y="4" fill="black"
                                                opacity="0.4">
                                                <animate fill="freeze" attributeName="opacity"
                                                    begin="svgSpinnersBarsFade0.begin+0.105s" dur="0.525s"
                                                    values="1;0.2" />
                                            </rect>
                                            <rect width="6" height="14" x="17" y="4" fill="black"
                                                opacity="0.3">
                                                <animate id="svgSpinnersBarsFade1" fill="freeze" attributeName="opacity"
                                                    begin="svgSpinnersBarsFade0.begin+0.21s" dur="0.525s"
                                                    values="1;0.2" />
                                            </rect>
                                        </svg>
                                    </span>
                                    @if ($profile_image)
                                        <img src="{{ $profile_image->temporaryUrl() }}" width="100"
                                            class="rounded mr-4" alt="Uploaded image">
                                    @elseif ($existing_image_path)
                                        <img src="{{ Storage::url($existing_image_path) }}" width="100"
                                            class="rounded mr-4" alt="Existing image">
                                    @endif

                                </span>
                            </div>


                            @if ($isEditing)
                                <span class="col-span-3 text-right mt-4">

                                    <label class="inline-flex items-center me-5 cursor-pointer">
                                        <input type="checkbox" wire:model.live="passwordUpdate" class="sr-only peer"
                                            checked>
                                        <div
                                            class="relative w-11 h-6 bg-gray-200 rounded-full peer dark:bg-gray-700  peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-teal-600">
                                        </div>
                                        <span class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300">تغییر
                                            رمز
                                            کاربری</span>
                                    </label>
                                </span>
                            @endif

                            @if ($passwordUpdate)
                                <span class="col-span-2 text-right">
                                    <label class="font-bold text-sm">رمز عبور</label>
                                    <span class="text-red-700">*</span>
                                    <input type="password" required wire:model.live="password"
                                        class="mt-1 peer block h-10 w-full bg-white border border-slate-300 rounded-md text-sm shadow-sm focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                                        autocomplete="off" dir="rtl">
                                    @error('password')
                                        <p class="text-red-500">{{ $message }}</p>
                                    @enderror
                                </span>

                                <span class="col-span-2 text-right">
                                    <label class="font-bold text-sm">تایید رمز عبور</label>
                                    <span class="text-red-700">*</span>
                                    <input type="password" required wire:model.live="password_confirmation"
                                        class="mt-1 peer block h-10 w-full bg-white border border-slate-300 rounded-md text-sm shadow-sm focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                                        autocomplete="off" dir="rtl">
                                    @error('password_confirmation')
                                        <p class="text-red-500">{{ $message }}</p>
                                    @enderror
                                </span>
                            @endif

                            <!-- Submit Button (on a separate row) -->
                            <br>

                            <div class="w-full mb-4">
                                <button id="submitButton" type="submit" wire:target="image"
                                    class="bg-[#0499af] font-medium leading-normal w-full text-white mt-6 px-6 py-2 rounded-md 
                                    hover:text-black transition duration-150 ease-in-out hover:bg-[#37cce2]
                                    disabled:bg-gray-400 disabled:text-gray-200 disabled:cursor-not-allowed">

                                    {{ $isEditing ? 'به‌روزرسانی' : 'ذخیره' }}
                                </button>



                            </div>
                            <!-- Cancel/Close Button (on a separate row) -->
                            <div class="w-full mb-4">
                                <x-button-simple x-on:click="isOpen = false; $wire.call('resetForm')"
                                    class="text-sm mt-6 h-10 w-full bg-red-800 hover:bg-red-700 hover:text-black"
                                    title="لفو" />
                            </div>



                        </form>
                    </div>
                </div>



            </div>
        </div>
    @endrole

    <div x-data="{ confirm: @entangle('confirm') }" dir="rtl">
        <div x-show="confirm" style="display: none;"
            class="fixed inset-0 z-50 flex items-start justify-center bg-gray-900 bg-opacity-50">

            <!-- Modal Structure -->
            <div x-show="confirm" x-transition:enter="transition ease-out duration-500"
                x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-90"
                class="bg-white p-6 rounded-lg shadow-lg w-full max-w-3xl mt-12">


                <!-- Modal Header -->
                <h2 class="text-lg font-semibold text-gray-800 mb-4">
                    آیا مطمعن هستید کاربر ذیل را حذف کنید؟
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

    <table dir="rtl" style="width: 100%"
        class="mt-4 mb-4 text-sm text-center text-gray-500 border border-slate-100 dark:text-gray-400">
        <thead class="text-xs text-gray-50 bg-sky-900 uppercase dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-3 py-2 border border-slate-200">
                    #
                </th>
                <th scope="col" class="px-3 py-2 border border-slate-200 cursor-pointer">
                    <div class="flex justify-center">
                        <span>نام مکمل</span>
                    </div>
                </th>
                <th scope="col" class="px-3 py-2 border border-slate-200 cursor-pointer">
                    <div class="flex justify-center">
                        <span>نام کاربری</span>
                    </div>
                </th>
                <th scope="col" class="px-3 py-2 border border-slate-200 cursor-pointer">
                    <div class="flex justify-center">
                        <span>ایمیل</span>
                    </div>
                </th>
                <th scope="col" class="px-3 py-2 border border-slate-200 cursor-pointer">
                    <div class="flex justify-center">
                        <span>ولایت</span>
                    </div>
                </th>
                <th scope="col" class="px-3 py-2 border border-slate-200 cursor-pointer">
                    <div class="flex justify-center">
                        <span>صلاحیت</span>
                    </div>
                </th>
                @role(1)
                    <th scope="col" class="px-3 py-2 border border-slate-200 cursor-pointer">
                        <div class="flex justify-center">
                            <span>اعمال</span>
                        </div>
                    </th>
                @endrole
            </tr>
        </thead>
        <tbody>
            @foreach ($table_data as $index => $tb)
                <tr class="border-b cursor-pointer hover:bg-warning-400 dark:border-gray-700">
                    <td class="px-3 py-2 border border-slate-200 dark:text-white">
                        {{ ++$index }}
                    </td>
                    <td class="px-3 py-2 border border-slate-200 dark:text-white">
                        {{ $tb->full_name ?? '' }}
                    </td>
                    <td class="px-3 py-2 border border-slate-200 dark:text-white">
                        {{ $tb->user_name ?? '' }}
                    </td>
                    <td class="px-3 py-2 border border-slate-200 dark:text-white">
                        {{ $tb->email ?? '' }}
                    </td>
                    @foreach ($provinces as $pr)
                        @if ($pr->province_code == $tb->province_id)
                            <td class="px-3 py-2 border border-slate-200 dark:text-white">
                                {{ $pr->name ?? '' }}
                            </td>
                        @endif
                    @endforeach
                    @foreach ($positions as $ps)
                        @if ($ps->id == $tb->position)
                            <td class="px-3 py-2 border border-slate-200 dark:text-white">
                                {{ $ps->name ?? '' }}
                            </td>
                        @endif
                    @endforeach
                    @role(1)
                        <td class="px-2 py-2 border border-slate-200 dark:text-white">
                            <button @click=" @this.call('editUser', {{ $tb->id }}); @this.call('openForm',0) "
                                class=" text-gray-900 px-2 py-2 rounded">
                                <span class="text-xl px-3 pt-5"><i class="fa  fa-edit text-sky-600"></i></span>
                            </button>
                            <button onclick="openModal({{ $tb->id }})" class=" text-gray-900 px-2 py-2 rounded">
                                <span class="text-xl px-3 pt-5"><i class="fa  fa-trash text-red-600"></i></span>
                            </button>
                        </td>
                    @endrole
                </tr>
            @endforeach
        </tbody>
    </table>

</div>
</div>
</div>

@push('otherjs')
    <script>
        function openModal(id) {
            const confirmButton = document.getElementById("confirmButton");
            @this.confirm = true;
            confirmButton.onclick = function() {

                @this.deleteUser(id);
                closeModal();
            };
        }

        function closeModal() {
            @this.confirm = false;
        }
    </script>
    <script>
        document.addEventListener('livewire-upload-start', () => {
            let submitButton = document.getElementById("submitButton");
            submitButton.disabled = true;
        });

        document.addEventListener('livewire-upload-finish', () => {

            let submitButton = document.getElementById("submitButton");
            submitButton.disabled = false;
        });
    </script>

    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('recordCreate', function(data) {
                @this.isOpen = false;
            });
            Livewire.on('recordUpdate', function(data) {
                @this.isOpen = false;
            });
        });
    </script>
@endpush

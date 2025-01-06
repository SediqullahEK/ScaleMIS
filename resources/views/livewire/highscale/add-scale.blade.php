<div class="relative overflow-hidden bg-white shadow-md drop-shadow-md dark:bg-gray-800 sm:rounded-lg">

    <div wire:loading wire:target="addScale, updateScale, openForm">
        <x-live-loader />
    </div>
    <x-pages_comp.topheader pageTitle='معلومات ترازو ها بر نقشه افغانستان' />

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
            class="fixed inset-0 z-50 flex items-start justify-center bg-gray-900 bg-opacity-50 overflow-auto">
            <!-- Modal Structure -->
            <div x-show="isOpen" x-transition:enter="transition ease-out duration-500"
                x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-90"
                class="bg-white p-6 rounded-lg shadow-lg w-full max-w-3xl mt-12 overflow-y-auto max-h-screen">

                <!-- Modal Content -->
                <div class="h-auto overflow-y-auto">
                    <!-- Modal Header -->
                    <div class="flex justify-between items-center pb-4 border-b w-full">
                        <h2 class="text-xl font-semibold">
                            {{ $isEditing ? 'ویرایش ترازو' : 'افزودن ترازو جدید در نقشه' }}
                        </h2>
                        <button @click="isOpen = false; @this.call('resetForm');"
                            class="text-gray-500 hover:text-gray-700 text-4xl p-2">&times;</button>
                    </div>

                    <!-- Form -->
                    <form wire:submit.prevent="{{ $isEditing ? 'updateScale' : 'addScale' }}"
                        class="grid lg:grid-cols-4 md:grid-cols-2 sm:grid-cols-1 gap-4">
                        @csrf
                        @if ($isEditing)
                            <input type="hidden" wire:model.live="scaleId" />
                        @endif

                        <!-- Scale Selection -->
                        <span class="col-span-1 text-right">
                            <label class="font-bold text-sm">ترازو</label>
                            <span class="text-red-700">*</span>
                            {{-- @if (!$isEditing)
                                <select required wire:model.live="scale" {{ $isEditing ? 'disabled' : '' }}
                                    wire:change="scaleSelect"
                                    class="mt-1 peer block h-10 w-full bg-white border border-slate-300 rounded-md text-sm shadow-sm focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500">
                                    <option value="0" disabled hidden selected>یک ترازو را انتخاب کنید
                                    </option>
                                    @foreach ($scales as $sc)
                                        <option value="{{ $sc->id }}">{{ $sc->name }}</option>
                                    @endforeach
                                </select>
                            @else
                                <input type="text" disabled wire:model.live="edit_scale_name"
                                    class="mt-1 peer block h-10 w-full bg-white border border-slate-300 rounded-md text-sm shadow-sm placeholder-slate-400 focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                                    autocomplete="off" dir="rtl">
                            @endif --}}
                            <input type="text" wire:model.live="scale_name"
                                class="mt-1 peer block h-10 w-full bg-white border border-slate-300 rounded-md text-sm shadow-sm placeholder-slate-400 focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                                autocomplete="off" dir="rtl">
                            @error('scale')
                                <p class="text-red-500">{{ $message }}</p>
                            @enderror
                        </span>

                        <!-- Province Selection -->
                        <span class="col-span-1 text-right">
                            <label class="font-bold text-sm">ولایت</label>
                            <span class="text-red-700">*</span>
                            <select required wire:model.live="province"
                                class="mt-1 peer block h-10 w-full bg-blue border border-slate-300 rounded-md text-sm shadow-sm focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500">
                                <option value="0" disabled hidden selected>یک ولایت را انتخاب کنید</option>
                                @foreach ($provinces as $pr)
                                    <option value="{{ $pr->province_code }}">{{ $pr->name }}</option>
                                @endforeach
                            </select>
                            @error('province')
                                <p class="text-red-500">{{ $message }}</p>
                            @enderror
                        </span>
                        <span class="col-span-1 text-right">
                            <label class="font-bold text-sm">موقعیت</label>
                            <span class="text-red-700">*</span>
                            <input type="text" required wire:model.live="location"
                                class="mt-1 peer block h-10 w-full bg-white border border-slate-300 rounded-md text-sm shadow-sm placeholder-slate-400 focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                                autocomplete="off" dir="rtl">
                            @error('location')
                                <p class="text-red-500">{{ $message }}</p>
                            @enderror
                        </span>

                        <div class="flex">
                            <span class="w-1/2 text-right">
                                <label class="font-bold text-sm">عکس ترازو</label>
                                <input type="file" wire:model.live="scale_image" id="file-upload" accept="image/*"
                                    class="hidden" />
                                <label for="file-upload"
                                    class="cursor-pointer mt-1 block h-10 w-full bg-white border border-slate-300 rounded-md text-sm shadow-sm flex items-center justify-center text-gray-700 hover:bg-gray-100 focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500">
                                    انتخاب عکس
                                </label>
                                @error('scale_image')
                                    <p class="text-red-500">{{ $message }}</p>
                                @enderror
                            </span>

                            <span class="w-1/2 flex items-center justify-center">
                                <span wire:loading wire:target="scale_image"
                                    class="col-start-5 col-span-1 justify-self-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24">
                                        <rect width="6" height="14" x="1" y="4" fill="black">
                                            <animate id="svgSpinnersBarsFade0" fill="freeze" attributeName="opacity"
                                                begin="0;svgSpinnersBarsFade1.end-0.175s" dur="0.525s"
                                                values="1;0.2" />
                                        </rect>
                                        <rect width="6" height="14" x="9" y="4" fill="black" opacity="0.4">
                                            <animate fill="freeze" attributeName="opacity"
                                                begin="svgSpinnersBarsFade0.begin+0.105s" dur="0.525s"
                                                values="1;0.2" />
                                        </rect>
                                        <rect width="6" height="14" x="17" y="4" fill="black" opacity="0.3">
                                            <animate id="svgSpinnersBarsFade1" fill="freeze" attributeName="opacity"
                                                begin="svgSpinnersBarsFade0.begin+0.21s" dur="0.525s"
                                                values="1;0.2" />
                                        </rect>
                                    </svg>
                                </span>
                                @if ($scale_image)
                                    <img src="{{ $scale_image->temporaryUrl() }}" width="100" class="rounded"
                                        alt="Uploaded image">
                                @elseif ($existing_image_path)
                                    <img src="{{ Storage::url($existing_image_path) }}" width="100"
                                        class="rounded" alt="Existing image">
                                @endif

                            </span>
                        </div>

                        <span class="col-span-1 text-right">
                            <label class="font-bold text-sm">لتتیود</label>
                            <span class="text-red-700">*</span>
                            <input type="text" wire:model.live="latitude"
                                class="mt-1 peer block h-10 w-full bg-white border border-slate-300 cursor-pointer rounded-md text-sm shadow-sm placeholder-slate-400 focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                                autocomplete="off" dir="rtl">
                            @error('latitude')
                                <p class="text-red-500">{{ $message }}</p>
                            @enderror
                        </span>

                        <span class="col-span-1 text-right">
                            <label class="font-bold text-sm">لانگتتیود</label>
                            <span class="text-red-700">*</span>
                            <input type="text" wire:model.live="longitude"
                                class="mt-1 peer block h-10 w-full bg-white border border-slate-300 rounded-md text-sm shadow-sm placeholder-slate-400 focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                                autocomplete="off" dir="rtl">
                            @error('longitude')
                                <p class="text-red-500">{{ $message }}</p>
                            @enderror
                        </span>

                        <span class="col-span-1 text-right">
                            <label class="font-bold text-sm">کارمند ترازو</label>
                            <span class="text-red-700">*</span>
                            <input type="text" required wire:model.live="scale_employee"
                                class="mt-1 peer block h-10 w-full bg-white border border-slate-300 rounded-md text-sm shadow-sm placeholder-slate-400 focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                                autocomplete="off" dir="rtl">
                            @error('scale_employee')
                                <p class="text-red-500">{{ $message }}</p>
                            @enderror
                        </span>

                        <span class="col-span-1 text-right">
                            <label class="font-bold text-sm">شماره تماس</label>
                            <input type="number" wire:model.live="employee_phone"
                                class="mt-1 peer block h-10 w-full bg-white border border-slate-300 rounded-md text-sm shadow-sm placeholder-slate-400 focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                                autocomplete="off" dir="rtl">
                            @error('employee_phone')
                                <p class="text-red-500">{{ $message }}</p>
                            @enderror
                        </span>

                        <span class="col-span-1 text-right">
                            <label class="font-bold text-sm">شرکت</label>
                            <input type="text" wire:model.live="company"
                                class="mt-1 peer block h-10 w-full bg-white border border-slate-300 rounded-md text-sm shadow-sm placeholder-slate-400 focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                                autocomplete="off" dir="rtl">
                            @error('company')
                                <p class="text-red-500">{{ $message }}</p>
                            @enderror
                        </span>

                        <span class="col-span-1 text-right">
                            <label class="font-bold text-sm">حالت ترازو</label>
                            <span class="text-red-700">*</span>
                            <select required wire:model.live="status"
                                class="mt-1 peer block h-10 w-full bg-white border border-slate-300 rounded-md text-sm shadow-sm focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500">
                                <option value="0">غیر فعال</option>
                                <option value="1">فعال</option>

                            </select>
                            @error('status')
                                <p class="text-red-500">{{ $message }}</p>
                            @enderror
                        </span>
                        <span class="col-span-2 text-right">
                            <label class="font-bold text-sm">توضیحات</label>
                            <textarea wire:model.live="description"
                                class=" mt-1 peer block h-10 w-full bg-white border border-slate-300 rounded-md text-sm shadow-sm placeholder-slate-400 focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                                autocomplete="off" dir="rtl" placeholder="ـوضیحات ترازو را وارد کنید">
                                </textarea>
                            @error('description')
                                <p class="text-red-500">{{ $message }}</p>
                            @enderror
                        </span>

                        <!-- Add or Update Button -->
                        <x-button-simple type="submit" class="text-sm mt-6 h-10"
                            title="{{ $isEditing ? 'به‌روزرسانی' : 'ذخیره' }}" />
                        <x-button-simple x-on:click="isOpen = false; $wire.call('resetForm')"
                            class="text-sm mt-6 h-10 bg-red-800 hover:bg-red-700 hover:text-black" title="لفو" />

                    </form>
                </div>
            </div>

        </div>
    </div>

    <div class="overflow-x-auto p-4" dir="rtl">
        <div class="flex justify-between items-center mb-4">
            <div></div> <!-- Empty placeholder for spacing -->
            <div>
                <select wire:model.live='perPage' dir="ltr"
                    class="py-1 px-2 text-sm text-gray-700 bg-white border border-gray-200 rounded-md dark:bg-gray-900 dark:text-gray-300 dark:border-gray-600 focus:border-blue-400 dark:focus:border-blue-300 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40">
                    <option value="10" selected>10</option>
                    <option value="20">20</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                    <option value="500">500</option>
                </select>
            </div>
        </div>
        <table dir="rtl" style="width: 100%"
            class="w-full mt-4 text-sm text-center text-gray-500 border-slate-100 dark:text-gray-400">
            <thead class="text-xs text-gray-50 bg-sky-900 uppercase dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-3 py-2 border border-slate-200">
                        #
                    </th>
                    <th scope="col" class="px-3 py-2 border border-slate-200 cursor-pointer">
                        <div class="flex justify-center">
                            <span>ولایت</span>
                        </div>
                    </th>
                    <th scope="col" class="px-3 py-2 border border-slate-200 cursor-pointer">
                        <div class="flex justify-center">
                            <span>نام ترازو</span>
                        </div>
                    </th>
                    <th scope="col" class="px-3 py-2 border border-slate-200 cursor-pointer">
                        <div class="flex justify-center">
                            <span>شرکت</span>
                        </div>
                    </th>
                    <th scope="col" class="px-3 py-2 border border-slate-200 cursor-pointer">
                        <div class="flex justify-center">
                            <span>موقعیت</span>
                        </div>
                    </th>
                    <th scope="col" class="px-3 py-2 border border-slate-200 cursor-pointer">
                        <div class="flex justify-center">
                            <span>حالت</span>
                        </div>
                    </th>
                    <th scope="col" class="px-3 py-2 border border-slate-200 cursor-pointer">
                        <div class="flex justify-center">
                            <span>کارمند ترازو</span>
                        </div>
                    </th>
                    <th scope="col" class="px-3 py-2 border border-slate-200 cursor-pointer">
                        <div class="flex justify-center">
                            <span>شماره تماس</span>
                        </div>
                    </th>
                    <th scope="col" class="px-3 py-2 border border-slate-200 cursor-pointer">
                        <div class="flex justify-center">
                            <span>توضیحات</span>
                        </div>
                    </th>
                    <th scope="col" class="px-3 py-2 border border-slate-200 cursor-pointer">
                        <div class="flex justify-center">
                            <span>عمل</span>
                        </div>
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($table_data as $index => $tb)
                    <tr class="border-b cursor-pointer hover:bg-warning-400 dark:border-gray-700">
                        <td class="px-3 py-2 border border-slate-200 dark:text-white">
                            {{ ++$index }}
                        </td>
                        <td class="px-3 py-2 border border-slate-200 dark:text-white">
                            @foreach ($provinces as $pr)
                                @if ($tb->province_id == $pr->province_code)
                                    {{ $pr->name ?? '' }}
                                @endif
                            @endforeach
                        </td>
                        <td class="px-3 py-2 border border-slate-200 dark:text-white">
                            {{ $tb->scale_name ?? '' }}
                        </td>
                        <td class="px-3 py-2 border border-slate-200 dark:text-white">
                            {{ $tb->scale_company ?? '' }}
                        </td>
                        <td class="px-3 py-2 border border-slate-200 dark:text-white">
                            {{ $tb->location ?? '' }}
                        </td>
                        <td class="px-3 py-2 border border-slate-200 dark:text-white">
                            @if ($tb->status)
                                فعال
                            @else
                                غیر فعال
                            @endif
                        </td>
                        <td class="px-3 py-2 border border-slate-200 dark:text-white">
                            {{ $tb->scale_employee ?? '' }}
                        </td>
                        <td class="px-3 py-2 border border-slate-200 dark:text-white">
                            {{ $tb->employee_phone ?? '' }}
                        </td>
                        <td class="px-3 py-2 border border-slate-200 dark:text-white">
                            {{ $tb->description ?? '' }}
                        </td>
                        <td class="px-3 py-2 border border-slate-200 dark:text-white">
                            <button @click=" @this.call('editScale', {{ $tb->id }}); @this.call('openForm',0) "
                                class=" text-gray-900 px-4 py-2 rounded">
                                <span class="text-2xl px-3 pt-5"><i class="fa  fa-edit text-sky-600"></i></span>
                            </button>
                        </td>

                    </tr>
                @endforeach
            </tbody>
        </table>
        <nav id="pagination-links"
            class="flex flex-col items-start justify-between p-4 space-y-3 md:flex-row md:items-center md:space-y-0"
            aria-label="Table navigation">
            {{ $table_data->links() }}
        </nav>
    </div>
</div>

@push('otherjs')
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

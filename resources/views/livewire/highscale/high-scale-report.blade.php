@php
    use Carbon\Carbon;
    use Morilog\Jalali\Jalalian;

@endphp

@push('othercss')
    <style>
        /* Ensure the Select2 container has enough padding and height */
        .select2-container--default .select2-selection--multiple {
            padding: 0.5rem;
            height: auto;
            /* Ensures enough space for the placeholder */
            min-height: 2.5rem;
            /* This gives space for the text and padding */
            line-height: 1.5;
            /* Makes sure the text aligns properly */
            border-radius: 0.375rem;
            /* Tailwind rounded-md */
            border: 1px solid #d1d5db;
            /* Tailwind border-slate-300 */
            box-shadow: 0px 1px 2px rgba(0, 0, 0, 0.05);
            /* Tailwind shadow-sm */
        }

        /* Styling for the placeholder text */
        .select2-container--default .select2-selection--multiple .select2-selection__rendered .select2-selection__placeholder {

            color: #9ca3af;
            /* Tailwind placeholder-slate-400 color */
            font-size: 0.875rem;
            /* Tailwind text-sm font size */
            line-height: 1.5rem;
            /* Adjust this to match line-height */
        }

        /* Adjusting the size and layout of selected items */
        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: #f3f4f6;
            /* Tailwind bg-slate-100 */
            color: #1f2937;
            /* Tailwind text-slate-800 */
            border: 1px solid #e5e7eb;
            /* Tailwind border-slate-200 */
            border-radius: 0.375rem;
            padding: 0 0.25rem;
            margin: 0.125rem;
        }

        /* Adjust the height of the Select2 dropdown (for multiple selections) */
        .select2-container--default .select2-dropdown {
            max-height: 300px;
            /* Tailwind max-h-72 */
            overflow-y: auto;
        }
    </style>
@endpush

<div class="relative overflow-hidden bg-white shadow-md drop-shadow-md dark:bg-gray-800 sm:rounded-lg">

    <div x-data="{ isOpen: @entangle('isOpen') }">
        <div x-show="isOpen" style="display: none;"
            class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-50">
            <!-- Modal Structure -->
            <div id="modal" x-show="isOpen" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-90"
                class="bg-white p-5 rounded-md shadow-lg max-w-sm text-center">

                <!-- Modal Header -->
                <h2 class="text-lg font-semibold text-gray-800">
                    آیا مطمعن هستید این فایل را ایجاد کنید؟
                </h2>


                <!-- Buttons -->
                <div class="flex justify-center space-x-4 mt-4">
                    <button id="cancelButton" onclick="closeModal()"
                        class="bg-red-600 text-white py-2 px-4 rounded hover:bg-red-500 transition">
                        لغو
                    </button>
                    <button id="confirmButton"
                        class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-500 transition">
                        تایید
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div wire:loading wire:target="tableData, exportToExcel">
        <x-live-loader />
    </div>
    <div id="loader" style="display: none;">
        <x-live-loader />
    </div>


    <x-pages_comp.topheader pageTitle='راپور عواید از انتقالات' />

    <fieldset class="border border-solid border-gray-300 p-4 m-5" dir="rtl">
        <legend class="text-lg text-right">فلتر های راپور</legend>

        <!-- Full-width Search Input -->
        <div class="relative flex items-center mt-4">
            <span class="absolute left-3">
                <svg aria-hidden="true" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                    class="w-6 h-6 text-gray-400 dark:text-gray-500">
                    <path fill-rule="evenodd"
                        d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                        clip-rule="evenodd" />
                </svg>
            </span>
            <input type="text" wire:model.live.debounce.500ms="search" placeholder="جستجو نمبر بارنامه"
                class="block w-full py-2.5 pl-10 pr-5 text-gray-700 placeholder-gray-400 bg-white border border-gray-200 rounded-lg dark:bg-gray-900 dark:text-gray-300 dark:border-gray-600 focus:border-blue-400 dark:focus:border-blue-300 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40">
        </div>

        <!-- Select Elements in a Row -->
        <div class="grid grid-cols-3 gap-4 mt-4">
            @if (auth()->user()->position != 1)
                @foreach ($provinces as $pr)
                    <label class="font-bold text-sm mt-5">ولایت
                        <input type="text" value="{{ $pr->name }}" disabled
                            class=" w-full py-2 px-3 border border-slate-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 h-[40px]">
                    </label>
                @endforeach
            @else
                <div dir='rtl' wire:ignore class="mt-4">
                    <label class="font-bold text-sm">ولایت</label>
                    <select style="width: 100%;" wire:model.live='catProvince' x-init="$('#catprov').select2({
                        placeholder: 'تمام ولایات',
                    });
                    $('#catprov').on('change', function(e) {
                        @this.set('catProvince', null);
                        @this.set('catProvince', $(this).val());
                    });
                    $('#catprov').trigger('change');" id="catprov"
                        multiple
                        class="mt-1 w-full py-2 px-3 border border-slate-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500">
                        <option value="" selected>تمام ولایات</option>
                        @foreach ($provinces as $pr)
                            <option value="{{ $pr->province_code }}">{{ $pr->name }}</option>
                        @endforeach
                    </select>
                </div>
            @endif


            <div dir='rtl' wire:ignore class="mt-4">
                <label class="font-bold text-sm">ترازو</label>
                <select style="width: 100%; height: 200px;" wire:model='catScale' x-init="$('#catscale').select2({
                    placeholder: 'تمام ترازو ها',
                });
                $('#catscale').on('change', function(e) {
                    @this.set('catScale', null);
                    @this.set('catScale', $(this).val());
                });
                $('#catscale').trigger('change');" id="catscale"
                    multiple
                    class="mt-1 w-full py-2 px-3 border border-slate-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500">
                    <option value="" selected>تمام ترازو ها</option>

                    @if (count($scales) != 0)
                        @foreach ($scales as $sc)
                            <option value="{{ $sc->id }}">{{ $sc->name }}</option>
                        @endforeach
                    @else
                        <option value="" disabled>هیچ ترازو موجود نیست</option>
                    @endif
                </select>
            </div>

            <div dir='rtl' wire:ignore class="mt-4">
                <label class="font-bold text-sm">منرال</label>
                <select style="width: 100%; height: 200px;" wire:model.live.debounce.300ms='catMineral'
                    x-init="$('#catmin').select2({
                        placeholder: 'تمام منرال ها',
                    });
                    $('#catmin').on('change', function(e) {
                        @this.set('catMineral', null);
                        @this.set('catMineral', $(this).val());
                    });
                    $('#catmin').trigger('change');" id="catmin" multiple
                    class="mt-1 w-full py-2 px-3 border border-slate-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500">
                    <option value="" selected>تمام مواد معدنی</option>
                    @foreach ($minerals as $mn)
                        <option value="{{ $mn->id }}">{{ $mn->minral_name }}</option>
                    @endforeach
                </select>
            </div>


        </div>

        <!-- Date Picker Fields in a Row -->
        <div class="grid grid-cols-2 gap-4 mt-4">
            <div>
                <label class="font-bold text-sm">از تاریخ</label>
                <input type="text" readonly required wire:model.live.debounce.500ms="from_date"
                    class="from_date mt-1 peer block h-10 w-full bg-white border border-slate-300 rounded-md text-sm shadow-sm placeholder-slate-400 focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                    autocomplete="off" dir="rtl">
                @error('from_date')
                    <p class="text-red-500">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="font-bold text-sm">الی تاریخ</label>
                <input type="text" readonly required wire:model.live.debounce.500ms="to_date"
                    class="to_date mt-1 peer block h-10 w-full bg-white border border-slate-300 rounded-md text-sm shadow-sm placeholder-slate-400 focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                    autocomplete="off" dir="rtl">
                @error('to_date')
                    <p class="text-red-500">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </fieldset>


    <div class="overflow-x-auto p-4" dir="rtl">
        <!-- Export Icons Section -->
        <div class="flex justify-end mb-4 space-x-4">

            <span class="ml-3">
                <img src="{{ asset('storage/scale_images/excel.png') }}" width="35"
                    class="rounded cursor-pointer transform transition-all duration-300 hover:scale-110"
                    title="Export to Excel" onclick="openModal('Excel')">
            </span>
            <span>
                <img src="{{ asset('storage/scale_images/print.png') }}" width="35"
                    class="rounded cursor-pointer transform transition-all duration-300 hover:scale-110"
                    title="Print" onclick="openModal('Print')">
            </span>
            <span>
                <img onclick="openModal('PDF')" src="{{ asset('storage/scale_images/pdf.png') }}" width="35"
                    class="rounded cursor-pointer transform transition-all duration-300 hover:scale-110"
                    title="Export to PDF">
            </span>
            <span>
                <select wire:model.live='perPage' dir="ltr"
                    class="py-1 px-2 text-sm text-gray-700 bg-white border border-gray-200 rounded-md dark:bg-gray-900 dark:text-gray-300 dark:border-gray-600 focus:border-blue-400 dark:focus:border-blue-300 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40">
                    <option value="10" selected>10</option>
                    <option value="20">20</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                    <option value="500">500</option>
                </select>
            </span>
        </div>






        <!-- Data Table -->
        <table dir="rtl"
            class="w-full text-sm text-center text-gray-500 dark:text-gray-400 border-separate border-spacing-0 border border-slate-100">
            <thead class="text-xs text-gray-50 bg-sky-900 uppercase dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-2 py-2 border border-slate-200">#</th>

                    <th scope="col" class="px-2 py-2 border cursor-pointer border-slate-200"
                        wire:click="sortBy('provinces.name')">
                        ولایت
                        @if ($sortField === 'provinces.name')
                            <span>{{ $sortDirection === 'desc' ? '▲' : '▼' }}</span>
                        @endif
                    </th>

                    <th scope="col" class="px-2 py-2 border cursor-pointer border-slate-200"
                        wire:click="sortBy('scale.name')">
                        نام ترازو
                        @if ($sortField === 'scale.name')
                            <span>{{ $sortDirection === 'desc' ? '▲' : '▼' }}</span>
                        @endif
                    </th>

                    <th scope="col" class="px-2 py-2 border cursor-pointer border-slate-200"
                        wire:click="sortBy('customers.name')">
                        اسم مشتری / شرکت
                        @if ($sortField === 'customers.name')
                            <span>{{ $sortDirection === 'desc' ? '▲' : '▼' }}</span>
                        @endif
                    </th>

                    <th scope="col" class="px-2 py-2 border cursor-pointer border-slate-200"
                        wire:click="sortBy('minrals.minral_name')">
                        نوع منرال
                        @if ($sortField === 'minrals.minral_name')
                            <span>{{ $sortDirection === 'desc' ? '▲' : '▼' }}</span>
                        @endif
                    </th>

                    <th scope="col" class="px-2 py-2 border cursor-pointer border-slate-200"
                        wire:click="sortBy('mineral_net_weight')">
                        وزن خالص
                        @if ($sortField === 'mineral_net_weight')
                            <span>{{ $sortDirection === 'desc' ? '▲' : '▼' }}</span>
                        @endif
                    </th>

                    <th scope="col" class="px-2 py-2 border cursor-pointer border-slate-200"
                        wire:click="sortBy('Total_Revenue')">
                        پول توزین
                        @if ($sortField === 'Total_Revenue')
                            <span>{{ $sortDirection === 'desc' ? '▲' : '▼' }}</span>
                        @endif
                    </th>

                    <th scope="col" class="px-2 py-2 border cursor-pointer border-slate-200"
                        wire:click="sortBy('cars')">
                        تعداد موتر
                        @if ($sortField === 'cars')
                            <span>{{ $sortDirection === 'desc' ? '▲' : '▼' }}</span>
                        @endif
                    </th>

                    <th scope="col" class="px-2 py-2 border cursor-pointer border-slate-200"
                        wire:click="sortBy('from_date')">
                        شروع انتقالات
                        @if ($sortField === 'from_date')
                            <span>{{ $sortDirection === 'desc' ? '▲' : '▼' }}</span>
                        @endif
                    </th>

                    <th scope="col" class="px-2 py-2 border cursor-pointer border-slate-200"
                        wire:click="sortBy('to_date')">
                        ختم انتقالات
                        @if ($sortField === 'to_date')
                            <span>{{ $sortDirection === 'desc' ? '▲' : '▼' }}</span>
                        @endif
                    </th>
                </tr>
            </thead>

            <tbody wire:init="loadTableData">
                <div wire:loading.delay
                    wire:target="search, to_date, from_date, perPage, catProvince, catMineral, catScale, sortBy">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="black">
                        <rect width="6" height="14" x="1" y="4">
                            <animate attributeName="opacity" begin="0s" dur="0.5s" values="1;0.2"
                                repeatCount="indefinite" />
                        </rect>
                        <rect width="6" height="14" x="9" y="4" opacity="0.3">
                            <animate attributeName="opacity" begin="0.2s" dur="0.5s" values="1;0.2"
                                repeatCount="indefinite" />
                        </rect>
                        <rect width="6" height="14" x="17" y="4" opacity="0.4">
                            <animate attributeName="opacity" begin="0.4s" dur="0.5s" values="1;0.2"
                                repeatCount="indefinite" />
                        </rect>
                    </svg>
                </div>
                @if ($table_data->isNotEmpty())
                    @foreach ($table_data as $index => $tb)
                        <tr class="border-b hover:bg-warning-400 dark:border-gray-700">
                            <td class="px-2 py-1 font-medium text-gray-900 border border-slate-200 dark:text-white">
                                {{ ++$index }}
                            </td>
                            <td class="px-2 py-1 font-medium text-gray-900 border border-slate-200 dark:text-white">
                                {{ $tb->province_name ?? '' }}
                            </td>
                            <td class="px-2 py-1 font-medium text-gray-900 border border-slate-200 dark:text-white">
                                {{ $tb->scale_name ?? '' }}
                            </td>
                            <td class="px-2 py-1 font-medium text-gray-900 border border-slate-200 dark:text-white">
                                {{ $tb->customer_name ?? '' }}
                            </td>
                            <td class="px-2 py-1 text-gray-900 border border-slate-200">{{ $tb->minral_name ?? '' }}
                            </td>
                            <td class="px-2 py-1 text-gray-900 border border-slate-200">
                                {{ $tb->mineral_net_weight ?? '' }}
                                @foreach ($units as $u)
                                    @if ($tb->unit_id == $u->id)
                                        تن
                                    @endif
                                @endforeach
                            </td>
                            <td class="px-2 py-1 text-gray-900 border border-slate-200">{{ $tb->Total_Revenue ?? '' }}
                                افغانی
                            </td>
                            <td class="px-2 py-1 text-gray-900 border border-slate-200">{{ $tb->cars ?? '' }}
                            </td>
                            @php
                                $from_date = $tb->from_date
                                    ? Jalalian::fromCarbon(Carbon::parse($tb->from_date))->format('Y-m-d')
                                    : '';
                                $to_date = $tb->to_date
                                    ? Jalalian::fromCarbon(Carbon::parse($tb->to_date))->format('Y-m-d')
                                    : '';
                            @endphp
                            <td class="px-2 py-1 text-gray-900 border border-slate-200">{{ $from_date ?? '' }}</td>
                            <td class="px-2 py-1 text-gray-900 border border-slate-200">{{ $to_date ?? '' }}</td>
                        </tr>
                    @endforeach
                @elseif ($search != '' && !$table_data->isNotEmpty())
                    <tr>
                        <td colspan="11" class="text-right text-3xl font-bold py-4">بارنامه ذیل در سیستم موجود نیست
                        </td>
                    </tr>
                @elseif((count($catProvince) != 0 || count($catScale) != 0 || count($catMineral) != 0) && !$table_data->isNotEmpty())
                    <tr>
                        <td colspan="11" class="text-right text-3xl font-bold py-4">نظر به فلتر بالا دیتا در سیستم
                            موجود نیست
                        </td>
                    </tr>
                @else
                    <x-live-loader />
                @endif
            </tbody>
        </table>

        <!-- Pagination Section -->
        <nav id="pagination-links" class="flex justify-between items-center mt-4">
            @if ($table_data->isNotEmpty())
                <div class="flex items-center space-x-2 space-x-reverse">
                    <span>{{ $table_data->links() }}</span>
                    <span wire:loading wire:target="previousPage, nextPage, gotoPage">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="black">
                            <rect width="6" height="14" x="1" y="4">
                                <animate attributeName="opacity" begin="0s" dur="0.5s" values="1;0.2"
                                    repeatCount="indefinite" />
                            </rect>
                            <rect width="6" height="14" x="9" y="4" opacity="0.3">
                                <animate attributeName="opacity" begin="0.2s" dur="0.5s" values="1;0.2"
                                    repeatCount="indefinite" />
                            </rect>
                            <rect width="6" height="14" x="17" y="4" opacity="0.4">
                                <animate attributeName="opacity" begin="0.4s" dur="0.5s" values="1;0.2"
                                    repeatCount="indefinite" />
                            </rect>
                        </svg>
                    </span>
                </div>
            @endif
        </nav>
    </div>




</div>

@push('otherjs')
    <script>
        function openModal(action) {
            const confirmButton = document.getElementById("confirmButton");

            @this.isOpen = true;
            confirmButton.onclick = function() {
                if (action === 'Excel') {
                    @this.exportToExcel();
                } else if (action === 'Print') {
                    document.getElementById('loader').style.display = 'block';
                    @this.exportPdf('print');
                } else if (action === 'PDF') {
                    document.getElementById('loader').style.display = 'block';
                    @this.exportPdf('pdf');
                }
                closeModal();
            };

        }


        function closeModal() {
            @this.isOpen = false;
        }
    </script>

    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('redirectToDownload', (url) => {
                window.open(url, '_blank');
                document.getElementById('loader').style.display = 'none';
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $(".to_date").pDatepicker({
                initialValue: false,
                autoClose: true,
                calendar: {
                    persian: {
                        locale: 'en'
                    }
                },
                onSelect: function(unix) {
                    @this.set('to_date', new persianDate(unix).format('YYYY-MM-DD'), true);
                },
            });
            $(".from_date").pDatepicker({
                initialValue: false,
                autoClose: true,
                calendar: {
                    persian: {
                        locale: 'en'
                    }
                },
                onSelect: function(unix) {
                    @this.set('from_date', new persianDate(unix).format('YYYY-MM-DD'), true);
                }
            });

        });
    </script>
@endpush

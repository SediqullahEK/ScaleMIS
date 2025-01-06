@php
    use Carbon\Carbon;
    use Morilog\Jalali\Jalalian;

@endphp

<div class="relative overflow-hidden bg-white shadow-md drop-shadow-md dark:bg-gray-800 sm:rounded-lg">

    <div wire:loading wire:target="getScaleRevenueReportByDate , loadScaleRevenueReport">
        <x-live-loader />
    </div>
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

    <x-pages_comp.topheader pageTitle='داشبورد عمومی ترازو های بلند تناژ افغانستان' />
    <div dir="rtl" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 my-7 mx-4">

        <!-- Card 1 -->
        <div class="bg-sky-900 rounded-xl shadow-lg shadow-slate-900/50 p-4 flex justify-between items-center">
            <div>
                <h2 class="text-lg sm:text-xl font-bold text-slate-50 mb-1">مجموع عواید ماه قبل</h2>
                <span class="text-sm sm:text-lg font-bold text-slate-50">{{ $last_month_revenue }} افغانی</span>
            </div>
            <span class="flex-shrink-0">
                <i class="fas fa-dollar-sign text-amber-400 text-4xl sm:text-6xl"></i>
            </span>
        </div>

        <!-- Card 2 -->
        <div class="bg-sky-900 rounded-xl shadow-lg shadow-slate-900/50 p-4 flex justify-between items-center">
            <div>
                <h2 class="text-lg sm:text-xl font-bold text-slate-50 mb-1">مجموع عواید ماه جاری</h2>
                <span class="text-sm sm:text-lg font-bold text-slate-50">{{ $current_month_revenue }} افغانی</span>
            </div>
            <span class="flex-shrink-0">
                <i class="fas fa-calendar text-amber-400 text-4xl sm:text-6xl"></i>
            </span>
        </div>

        <!-- Card 3 -->
        <div class="bg-sky-900 rounded-xl shadow-lg shadow-slate-900/50 p-4 flex justify-between items-center">
            <div>
                <h2 class="text-lg sm:text-xl font-bold text-slate-50 mb-1">مجموع عواید سال جاری</h2>
                <span class="text-sm sm:text-lg font-bold text-slate-50">{{ $current_year_revenue }} افغانی</span>
            </div>
            <span class="flex-shrink-0">
                <i class="fas fa-calendar-alt text-amber-400 text-4xl sm:text-6xl"></i>
            </span>
        </div>

    </div>


    <fieldset class="border border-solid border-gray-300 p-2 m-5 " dir="rtl">
        <legend class="text-lg text-right">جستجو</legend>

        <form wire:submit.prevent="getScaleRevenueReportByDate"
            class="grid lg:grid-cols-4 md:grid-cols-2 sm:grid-cols-1 gap-4">

            <!-- Scale Selection -->
            <span class="col-span-1 text-right">
                <label class="font-bold text-sm">ترازو</label>
                <select wire:model.defer="selectedOption"
                    class="mt-1 peer block h-10 w-full bg-white border border-slate-300 rounded-md text-sm shadow-sm placeholder-slate-400 focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500">
                    <option value="0">همه ترازو ها</option>
                    @foreach ($scales as $scale)
                        <option value="{{ $scale->id }}">{{ $scale->name }}</option>
                    @endforeach
                </select>
            </span>

            <!-- From Date -->
            <span class="col-span-1 text-right">
                <label class="font-bold text-sm">از تاریخ</label>
                <input type="text" readonly required wire:model.defer="from_date"
                    class="from_date mt-1 peer block h-10 w-full bg-white border border-slate-300 rounded-md text-sm shadow-sm placeholder-slate-400 focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                    autocomplete="off" dir="rtl">
                @error('from_date')
                    <p class="text-red-500">{{ $message }}</p>
                @enderror
            </span>

            <!-- To Date -->
            <span class="col-span-1 text-right">
                <label class="font-bold text-sm">الی تاریخ</label>
                <input type="text" readonly required wire:model.defer="to_date"
                    class="to_date mt-1 peer block h-10 w-full bg-white border border-slate-300 rounded-md text-sm shadow-sm placeholder-slate-400 focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                    autocomplete="off" dir="rtl">
                @error('to_date')
                    <p class="text-red-500">{{ $message }}</p>
                @enderror
            </span>

            <!-- Submit Button -->
            <x-button-simple type="submit" wire:model="submit_data" class="text-sm mt-6 h-10" title="جستجو راپور" />
        </form>

    </fieldset>
    <hr>
    @if ($searched && !count($table_data))
        <h1 class="text-right text-3xl font-bold mt-3 mr-3 mb-4">
            در تاریخ انتخاب شده دیتا موجود نیست
        </h1>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-4" dir="rtl">
        <div
            class="col-span-1 flex flex-col items-center px-4 py-8 bg-white shadow-lg rounded-lg transition-transform transform hover:scale-105">
            <div wire:ignore id="weights_chart" class="w-full h-72"></div>
        </div>
        <div
            class="col-span-1 flex flex-col items-center px-4 py-8 bg-white shadow-lg rounded-lg transition-transform transform hover:scale-105">
            <div wire:ignore id="provinces_revenue_chart" class="w-full h-72"></div>
        </div>
        <div
            class="col-span-1 flex flex-col items-center px-4 py-8 bg-white shadow-lg rounded-lg transition-transform transform hover:scale-105">
            <div wire:ignore id="scales_revenue_chart" class="w-full h-72"></div>
        </div>
        <div
            class="col-span-1 flex flex-col items-center px-4 py-8 bg-white shadow-lg rounded-lg transition-transform transform hover:scale-105">
            <div wire:ignore id="cars_chart" class="w-full h-72"></div>
        </div>

    </div>



    @if (count($table_data))
        <div class="flex justify-end m-5">
            <div class="overflow-x-auto w-full">
                <table dir="rtl" style="width: 100%"
                    class="w-full mt-4 text-sm text-center text-gray-500 border-slate-100 dark:text-gray-400">
                    <thead class="text-xs text-gray-50 bg-sky-900 uppercase dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-2 py-1 border border-slate-200">
                                #
                            </th>
                            <th scope="col" class="px-2 py-1 border cursor-pointer border-slate-200">
                                <div class="flex justify-center">
                                    <span>ولایت</span>
                                </div>
                            </th>
                            <th scope="col" class="px-2 py-1 cursor-pointer border border-slate-200">
                                <div class="flex justify-center">
                                    <span>نام ترازو</span>
                                </div>
                            </th>
                            <th scope="col" class="px-2 py-1 cursor-pointer border border-slate-200"
                                wire:click="setSort('phone')">
                                <div class="flex justify-center">
                                    <span>نوع منرال</span>
                                </div>
                            </th>
                            <th scope="col" class="px-2 py-1 cursor-pointer border border-slate-200">
                                <div class="flex justify-center">
                                    <span>عواید</span>
                                </div>
                            </th>
                            <th scope="col" class="px-2 py-1 cursor-pointer border border-slate-200">
                                <div class="flex justify-center">
                                    <span>وزن مجموعی منرال</span>
                                </div>
                            </th>
                            <th scope="col" class="px-2 py-1 cursor-pointer border border-slate-200">
                                <div class="flex justify-center">
                                    <span>تعداد موتر</span>
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($table_data as $index => $tb)
                            <tr class="border-b cursor-pointer hover:bg-warning-400 dark:border-gray-700">
                                <td
                                    class="px-2 py-1 font-medium text-gray-900 border border-slate-200 dark:text-white">
                                    {{ ++$index }}
                                </td>
                                <td
                                    class="px-2 py-1 font-medium text-gray-900 border border-slate-200 dark:text-white">
                                    {{ $tb->province ?? '' }}
                                </td>
                                <td
                                    class="px-2 py-1 font-medium text-gray-900 border border-slate-200 dark:text-white">
                                    {{ $tb->scale_name ?? '' }}
                                </td>
                                <td
                                    class="px-2 py-1 font-medium text-gray-900 border border-slate-200 dark:text-white">
                                    {{ $tb->mineral_name ?? '' }}
                                </td>
                                <td
                                    class="px-2 py-1 font-medium text-gray-900 border border-slate-200 dark:text-white">
                                    {{ $tb->total_revenue ?? '' }}
                                </td>
                                <td
                                    class="px-2 py-1 font-medium text-gray-900 border border-slate-200 dark:text-white">
                                    {{ $tb->total_mineral_weight ?? '' }}
                                </td>
                                <td
                                    class="px-2 py-1 font-medium text-gray-900 border border-slate-200 dark:text-white">
                                    {{ $tb->cars ?? '' }}
                                </td>
                            </tr>
                        @endforeach
                        <tr class="border-b cursor-pointer hover:bg-warning-400 dark:border-gray-700">
                            <td class="px-2 py-1 font-bold text-gray-900 border border-slate-200 dark:text-white">
                                {{ 'مجموع' }}
                            </td>
                            <td class="px-2 py-1 font-medium text-gray-900 dark:text-white"></td>
                            <td class="px-2 py-1 font-medium text-gray-900 dark:text-white"></td>
                            <td class="px-2 py-1 font-medium text-gray-900 dark:text-white"></td>
                            <td class="px-2 py-1 font-bold text-gray-900 border border-slate-200 dark:text-white">
                                {{ $total_revenue }} افغانی
                            </td>
                            <td class="px-2 py-1 font-bold text-gray-900 border border-slate-200 dark:text-white">
                                {{ $total_weight }} تن
                            </td>
                            <td class="px-2 py-1 font-bold text-gray-900 border border-slate-200 dark:text-white">
                                {{ $total_cars }} موتر
                            </td>
                        </tr>
                    </tbody>

                </table>
            </div>
        </div>

    @endif
</div>


@push('otherjs')
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @this.call('loadScaleRevenueReport');
            console.log('Called loadScaleRevenueReport');
        });
    </script>

    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('loadChartData', function(data) {
                if (data[0].length != 0) {
                    var revenuesChart = Highcharts.charts[Highcharts.charts.length - 1];
                    var weightsChart = Highcharts.charts[Highcharts.charts.length - 2];
                    if (revenuesChart) {
                        revenuesChart.destroy();
                    }
                    if (weightsChart) {
                        weightsChart.destroy();
                    }


                    const cars_chart_data = [];
                    const cars_chart_data_names = [...new Set(@this.table_data.map(item => item
                        .mineral_name))];
                    cars_chart_data_names.forEach(name => {
                        const totalCars = @this.table_data
                            .filter(item => item.mineral_name === name)
                            .reduce((sum, item) => sum + parseFloat(item.cars), 0);
                        cars_chart_data.push({
                            name,
                            y: totalCars
                        });
                    });

                    const weightAggregate = [];

                    // Aggregate weights
                    data[0].forEach(item => {
                        // Check if the mineral_name already exists in the weightAggregate array
                        const existingMineral = weightAggregate.find(m => m.mineral_name === item
                            .mineral_name);

                        if (existingMineral) {
                            existingMineral.total_mineral_weight = Number(existingMineral
                                .total_mineral_weight) + Number(item.total_mineral_weight);
                        } else {
                            weightAggregate.push({
                                mineral_name: item.mineral_name,
                                total_mineral_weight: item.total_mineral_weight
                            });
                        }
                    });

                    const weights_chart_data = weightAggregate.map(item => ({
                        name: item.mineral_name,
                        y: Number(item
                            .total_mineral_weight
                        )
                    }));

                    const scales_revenue_data = data[1].map(item => ({
                        name: item.name,
                        y: parseFloat(item.Total_Revenue)
                    }));


                    const provinces_revenue = [];

                    data[0].forEach(d => {
                        const existingProvince = provinces_revenue.find(p => p.province === d
                            .province);

                        if (existingProvince) {
                            existingProvince.revenue = Number(existingProvince.revenue) + Number(d
                                .total_revenue);
                        } else {
                            provinces_revenue.push({
                                province: d.province,
                                revenue: Number(d.total_revenue)
                            });
                        }
                    });

                    console.log("province revenue: ", provinces_revenue);

                    const provinces_revenue_data = provinces_revenue.map(item => ({
                        name: item.province,
                        y: parseFloat(item.revenue)
                    }));

                    Highcharts.chart('scales_revenue_chart', {
                        chart: {
                            type: 'pie'
                        },
                        title: {
                            text: 'عواید بر اساس ترازو '
                        },
                        tooltip: {
                            pointFormat: '{series.name}: <b>{point.y:.1f} افغانی</b>'
                        },
                        plotOptions: {
                            pie: {
                                allowPointSelect: true,
                                cursor: 'pointer',
                                dataLabels: {
                                    enabled: true,
                                    format: '<b>{point.name}</b>: {point.y:.1f} افغانی'
                                }
                            }
                        },
                        series: [{
                            name: 'عواید',
                            colorByPoint: true,
                            data: scales_revenue_data
                        }]
                    });


                    Highcharts.chart('provinces_revenue_chart', {
                        chart: {
                            type: 'column'
                        },
                        title: {
                            text: 'عواید بر اساس ولایت'
                        },
                        xAxis: {
                            type: 'category',
                            labels: {
                                autoRotation: [-45, -90],
                                style: {
                                    fontSize: '13px',
                                    fontFamily: 'Verdana, sans-serif'
                                }
                            }
                        },
                        yAxis: {
                            min: 0,
                            title: {
                                text: 'افغانی'
                            }
                        },
                        legend: {
                            enabled: true
                        },
                        tooltip: {
                            pointFormat: 'عواید: <b>{point.y:.1f}</b>'
                        },
                        series: [{
                            name: 'عواید',
                            colors: [
                                '#9b20d9', '#5b30e7', '#277dbd', '#3667c9', '#7010f9',
                                '#691af3',
                                '#6225ed', '#5b30e7', '#533be1', '#4c46db', '#4551d5',
                                '#3e5ccf',
                                '#3667c9', '#2f72c3', '#277dbd', '#1f88b7', '#1693b1',
                                '#0a9eaa',
                                '#03c69b', '#00f194'
                            ],
                            colorByPoint: true,
                            groupPadding: 0,
                            data: provinces_revenue_data,
                            dataLabels: {
                                enabled: true,
                                rotation: -90,
                                color: '#FFFFFF',
                                align: 'right',
                                format: '{point.y:.1f}', // one decimal
                                y: 10, // 10 pixels down from the top
                                style: {
                                    fontSize: '13px',
                                    fontFamily: 'Verdana, sans-serif'
                                }
                            }
                        }]
                    });


                    // Highcharts.chart('provinces_revenue_chart', {
                    //     chart: {
                    //         type: 'pie'
                    //     },
                    //     title: {
                    //         text: 'عواید پروسس بر اساس ولایت'
                    //     },
                    //     tooltip: {
                    //         pointFormat: '{series.name}: <b>{point.y:.1f} افغانی</b>'
                    //     },
                    //     plotOptions: {
                    //         pie: {
                    //             allowPointSelect: true,
                    //             cursor: 'pointer',
                    //             dataLabels: {
                    //                 enabled: true,
                    //                 format: '<b>{point.name}</b>: {point.y:.1f} افغانی'
                    //             }
                    //         }
                    //     },
                    //     series: [{
                    //         name: 'عواید',
                    //         colorByPoint: true,
                    //         data: provinces_revenue_data
                    //     }]
                    // });

                    // Create pie chart for cars distribution.
                    Highcharts.chart('cars_chart', {
                        chart: {
                            type: 'pie'
                        },
                        title: {
                            text: 'توزیع موتر ها بر اساس منرال'
                        },
                        tooltip: {
                            pointFormat: '{series.name}: <b>{point.y} موتر</b>'
                        },
                        plotOptions: {
                            pie: {
                                allowPointSelect: true,
                                cursor: 'pointer',
                                dataLabels: {
                                    enabled: true,
                                    format: '<b>{point.name}</b>: {point.y} موتر'
                                }
                            }
                        },
                        series: [{
                            name: 'موتر',
                            colorByPoint: true,
                            data: cars_chart_data
                        }]
                    });

                    // Create pie chart for weights distribution.
                    Highcharts.chart('weights_chart', {
                        chart: {
                            type: 'pie'
                        },
                        title: {
                            text: '(ها)توزیع وزن منرال بر اساس ترازو'
                        },
                        tooltip: {
                            pointFormat: '{series.name}: <b>{point.y:.1f} تن</b>'
                        },
                        plotOptions: {
                            pie: {
                                allowPointSelect: true,
                                cursor: 'pointer',
                                dataLabels: {
                                    enabled: true,
                                    format: '<b>{point.name}</b>: {point.y:.1f} تن'
                                }
                            }
                        },
                        series: [{
                            name: 'وزن',
                            colorByPoint: true,
                            data: weights_chart_data
                        }]
                    });
                } else {

                    var revenues_chart = Highcharts.charts[Highcharts.charts.length - 1];
                    var weights_chart = Highcharts.charts[Highcharts.charts.length - 2];
                    var cars_chart = Highcharts.charts[Highcharts.charts.length - 3];
                    var province_chart = Highcharts.charts[Highcharts.charts.length - 4];

                    if (revenuesChart || weightsChart || cars_chart || province_chart) {

                        revenues_chart.destroy();
                        weights_chart.destroy();
                        cars_chart.destroy();
                        province_chart.destroy();
                    }

                }

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

<?php

namespace App\Livewire\Highscale;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Morilog\Jalali\Jalalian;
use Jenssegers\Date\Date;
use Carbon\Carbon;


class ScaleDashboard extends Component
{
    use WithPagination;

    public $table_data = [];
    public $to_date;

    public $searched = 0;
    public $total = 0;
    public $total_revenue = 0;
    public $total_weight = 0;

    public $submit_data = 0;
    public $last_month_revenue = 0;
    public $current_month_revenue = 0;
    public $current_year_revenue = 0;
    public $total_cars = 0;
    public $from_date;
    public $selectedOption = 0;
    public $to_gregorianDate = '';
    public $from_gregorianDate = '';
    public $revenues_chart_data = [];

    public $weights_chart_data = [];
    public $cars_chart_category = [];
    public $revenues_chart_labels = [];

    protected $rules = [
        'to_date' => 'required|date|after_or_equal:from_date',
    ];
    protected $messages = [
        'from_date.required' => 'یک تاریخ را انتخاب کنید',
        'to_date.required' => 'یک تاریخ را انتخاب کنید',
        'to_date.after_or_equal' => 'این تاریخ باید بعد از تاریخ قبلی و قبل از امروز باشد',
    ];

    protected $listeners = ['loadScaleRevenueReport'];

    public function loadScaleRevenueReport()
    {
        $this->getScaleRevenueReportByDate(first: 1);
    }


    public function shamsiToGregorian($shamsiDate)
    {
        try {

            $jalaliDate = Jalalian::fromFormat('Y-m-d', $shamsiDate);

            $gregorianDate = $jalaliDate->toCarbon();

            return $gregorianDate->format('Y-m-d');
        } catch (\Exception $e) {
            return "Error: Unable to parse Shamsi date. " . $e->getMessage();
        }
    }

    public static function gregorianToShamsi($gregorianDate)
    {
        return Jalalian::fromCarbon($gregorianDate)->format('Y-m-d');;
    }

    public function getScaleRevenueReportByDate($first = 0)
    {
        $this->searched = 1;

        $this->total_revenue = 0;
        $this->total_weight = 0;
        $this->total_cars = 0;

        $this->total = 0;

        $this->current_year_revenue = 0;

        if ($first == 0) {
            $this->validate();

            if (!empty($this->from_date)) {
                $this->from_gregorianDate = $this->shamsiToGregorian($this->from_date);
            }

            if (!empty($this->to_date)) {
                $this->to_gregorianDate = $this->shamsiToGregorian($this->to_date);
            }

            $this->table_data = DB::table('scale_system.weight')
                ->when(auth()->user()->position != 1, function ($query) {
                    $query->where('provinces.province_code', '=', auth()->user()->province_id);
                })
                ->when(!empty($this->selectedOption), function ($query) {
                    $query->where('scale.id', $this->selectedOption);
                })
                ->when($this->from_date !== '' && $this->to_date !== '', function ($query) {

                    $query->whereBetween('weight.created_at', [$this->from_gregorianDate, $this->to_gregorianDate]);
                })
                ->when($this->from_date === '' && $this->to_date !== '', function ($query) {
                    $query->where('weight.created_at', '<=', $this->to_gregorianDate);
                })
                ->join('scale', 'weight.scale_id', '=', 'scale.id')
                ->join('provinces', 'scale.department_id', '=', 'provinces.province_code')
                ->join('minrals', 'minrals.id', '=', 'weight.mineral_id')
                ->select(
                    'scale.name as scale_name',
                    'minrals.minral_name as mineral_name',
                    'provinces.name AS province',
                    'provinces.province_code AS province_id',
                    DB::raw('COUNT(weight.id) as cars'),
                    DB::raw('SUM(mineral_net_weight) AS total_mineral_weight'),
                    DB::raw("SUM(CASE WHEN discharge_place = 'CHECKED' THEN 200 ELSE mineral_net_weight * 10 END) as total_revenue")
                )

                ->groupBy('scale.id', 'minrals.id', 'provinces.name')
                ->orderBy('scale_name')
                ->get();
        } else {
            $this->table_data = DB::table('scale_system.weight')
                ->when(auth()->user()->position != 1, function ($query) {
                    $query->where('provinces.province_code', '=', auth()->user()->province_id);
                })
                ->join('scale', 'weight.scale_id', '=', 'scale.id')
                ->join('provinces', 'scale.department_id', '=', 'provinces.province_code')
                ->join('minrals', 'minrals.id', '=', 'weight.mineral_id')
                ->select(
                    'scale.name as scale_name',
                    'minrals.minral_name as mineral_name',
                    'provinces.name AS province',
                    'provinces.province_code AS province_id',

                    DB::raw('COUNT(weight.id) as cars'),
                    DB::raw('SUM(mineral_net_weight) AS total_mineral_weight'),
                    DB::raw("SUM(CASE WHEN discharge_place = 'CHECKED' THEN 200 ELSE mineral_net_weight * 10 END) as total_revenue")
                )

                ->groupBy('scale.id', 'minrals.id', 'provinces.name')
                ->orderBy('scale_name')
                ->get();
        }


        $totalItems = count($this->table_data);
        $counter = 0;

        $this->revenues_chart_data = [];
        $this->weights_chart_data = [];
        $this->cars_chart_data = [];
        $this->revenues_chart_labels = [];

        foreach ($this->table_data as $tb) {
            $counter++;
            $this->total_revenue += $tb->total_revenue;
            $this->total_weight += $tb->total_mineral_weight;
            $this->total_cars += $tb->cars;
        }
        if ($first == 0) {
            $scale_revenue = DB::connection('scale_system')->table('weight')
                ->when($this->selectedOption != 0, function ($query) {
                    $query->where('scale.id', $this->selectedOption);
                })
                ->when(auth()->user()->position != 1, function ($query) {
                    $query->where('provinces.province_code', '=', auth()->user()->province_id);
                })
                ->when($this->to_date !== '', function ($query) {
                    $query->whereBetween('weight.date', [$this->from_gregorianDate, $this->to_gregorianDate]);
                })
                ->leftjoin('scale', 'weight.scale_id', '=', 'scale.id')
                ->leftjoin('provinces', 'scale.department_id', '=', 'provinces.province_code')
                ->select(
                    'scale.name',
                    DB::raw('SUM(CASE WHEN discharge_place = "CHECKED" THEN 200 ELSE mineral_net_weight * 10 END) AS Total_Revenue')
                )

                ->groupBy('scale.name')
                ->get();
        } else {

            $scale_revenue = DB::connection('scale_system')->table('weight')
                ->when(auth()->user()->position != 1, function ($query) {
                    $query->where('provinces.province_code', '=', auth()->user()->province_id);
                })
                ->leftjoin('scale', 'weight.scale_id', '=', 'scale.id')
                ->leftjoin('provinces', 'scale.department_id', '=', 'provinces.province_code')
                ->select(
                    'scale.name',
                    DB::raw('SUM(CASE WHEN discharge_place = "CHECKED" THEN 200 ELSE mineral_net_weight * 10 END) AS Total_Revenue')
                )

                ->groupBy('scale.name')
                ->get();
        }

        if ($counter === $totalItems) {
            $this->dispatch('loadChartData', ($this->table_data), $scale_revenue);
        }
    }

    public function getRevenues($par)
    {

        if ($par === 'lastMonth') {

            $jalaliDate = Jalalian::fromCarbon(now()->subMonth());

            $year = $jalaliDate->format('Y');
            $month = $jalaliDate->format('m');

            $firstDayShamsi = Jalalian::fromFormat('Y-m-d', $year . '-' . $month . '-01')->format('Y-m-d');

            $lastDayShamsi = Jalalian::fromFormat('Y-m-d', $year . '-' . $month . '-' . $jalaliDate->getMonthDays())->format('Y-m-d');


            $revenues = DB::connection('scale_system')->table('weight')
                ->whereBetween('weight.created_at', [$this->shamsiToGregorian($firstDayShamsi), $this->shamsiToGregorian($lastDayShamsi)])
                ->when(auth()->user()->position != 1, function ($query) {
                    $query->where('provinces.province_code', '=', auth()->user()->province_id);
                })
                ->leftjoin('scale', 'weight.scale_id', '=', 'scale.id')
                ->leftjoin('provinces', 'scale.department_id', '=', 'provinces.province_code')

                ->select(
                    DB::raw("SUM(CASE WHEN discharge_place = 'CHECKED' THEN 200 ELSE mineral_net_weight * 10 END) as total_revenue"),
                )

                ->groupBy('weight.mineral_id', 'weight.discharge_place')
                ->get();


            foreach ($revenues as $lr) {
                $this->last_month_revenue += $lr->total_revenue;
            }

            return $this->last_month_revenue;
        } elseif ($par === 'currentMonth') {

            $jalaliDate = Jalalian::fromFormat('Y-m-d', $this->gregorianToShamsi(now()));

            $year = $jalaliDate->format('Y');
            $month = $jalaliDate->format('m');
            $day = $jalaliDate->format('d');

            $firstDayShamsi = Jalalian::fromFormat('Y-m-d', $year . '-' . $month . '-01')->format('Y-m-d');

            $lastDayShamsi = Jalalian::fromFormat('Y-m-d', $year . '-' . $month . '-' . $jalaliDate->getMonthDays())->format('Y-m-d');


            $revenues = DB::connection('scale_system')->table('weight')
                ->when(auth()->user()->position != 1, function ($query) {
                    $query->where('provinces.province_code', '=', auth()->user()->province_id);
                })
                ->whereBetween('weight.created_at', [$this->shamsiToGregorian($firstDayShamsi), $this->shamsiToGregorian($lastDayShamsi)])
                ->leftjoin('scale', 'weight.scale_id', '=', 'scale.id')
                ->leftjoin('provinces', 'scale.department_id', '=', 'provinces.province_code')
                ->select([
                    'scale.name',
                    'weight.mineral_id',
                    DB::raw('COUNT(weight.id) as cars'),
                    DB::raw('SUM(weight.mineral_net_weight) AS Total_Mineral_Weight'),
                    DB::raw("SUM(CASE WHEN discharge_place = 'CHECKED' THEN 200 ELSE mineral_net_weight * 10 END) as total_revenue"),
                    'weight.discharge_place',
                    'weight.discharge_place',
                ])
                ->groupBy('scale.name', 'weight.mineral_id', 'weight.discharge_place')
                ->get();

            foreach ($revenues as $lr) {
                $this->current_month_revenue += $lr->total_revenue;
            }

            return $this->current_month_revenue;
        } else if ($par === 'currentYear') {
            $jalaliDate = Jalalian::fromFormat('Y-m-d', $this->gregorianToShamsi(now()));

            $year = $jalaliDate->getYear();

            $firstDayShamsiYear = Jalalian::fromFormat('Y-m-d', $year . '-01-01')->format('Y-m-d');

            $lastDayShamsiYear = Jalalian::fromFormat('Y-m-d', $year . '-12-31')->format('Y-m-d');

            $revenues = DB::connection('scale_system')->table('weight')
                ->when(auth()->user()->position != 1, function ($query) {
                    $query->where('provinces.province_code', auth()->user()->province_id);
                })
                ->whereBetween('weight.created_at', [$this->shamsiToGregorian($firstDayShamsiYear), $this->shamsiToGregorian($lastDayShamsiYear)])
                ->whereYear('weight.created_at', date('Y'))

                ->leftjoin('scale', 'weight.scale_id', '=', 'scale.id')
                ->leftjoin('provinces', 'scale.department_id', '=', 'provinces.province_code')
                ->select(
                    'scale.name',
                    'weight.mineral_id',
                    DB::raw('COUNT(weight.id) as cars'),
                    DB::raw('SUM(weight.mineral_net_weight) AS Total_Mineral_Weight'),
                    DB::raw("SUM(CASE WHEN discharge_place = 'CHECKED' THEN 200 ELSE mineral_net_weight * 10 END) as total_revenue"),
                    'weight.discharge_place'
                )

                ->groupBy('scale.name', 'weight.mineral_id', 'weight.discharge_place')
                ->get();

            foreach ($revenues as $lr) {
                $this->current_year_revenue += $lr->total_revenue;
            }

            return $this->current_year_revenue;
        }
    }


    public function render()
    {
        $this->last_month_revenue = 0;
        $this->to_date = Jalalian::fromDateTime(now())->format('Y-m-d');
        $this->current_month_revenue = 0;
        $this->current_year_revenue = 0;

        return view('livewire.highscale.scale-dashboard', [
            'scales' => DB::connection('scale_system')->table('scale')
                ->select('id', 'name')
                ->when(auth()->user()->position != 1, function ($query) {
                    $query->where('department_id', auth()->user()->province_id);
                })
                ->distinct()
                ->get(),
            'table_data' => $this->table_data,
            'last_month_revenue' => $this->getRevenues('lastMonth'),
            'current_month_revenue' => $this->getRevenues('currentMonth'),
            'current_year_revenue' => $this->getRevenues('currentYear'),

        ]);
    }
}

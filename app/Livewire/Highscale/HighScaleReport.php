<?php

namespace App\Livewire\Highscale;

use Carbon\Carbon;
use App\Models\Mineral;
use Livewire\Component;
use App\Exports\WeightExport;
use App\Models\Province;
use App\Models\Weight;
use App\Models\Scale;
use Livewire\WithPagination;
use Morilog\Jalali\Jalalian;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Cache;
use PDF;


class HighScaleReport extends Component
{
    use WithPagination;



    public $perPage = 10;

    public $table_links;

    public $action = '';

    public $sortField = 'weight.date';

    public $sortDirection = 'asc';

    public $isOpen = false;

    public $showLoader = false;

    public $search = '';

    public $catMineral = [];

    public $catProvince = [];

    public $to_date;

    public $from_date;

    public $from_gregorianDate;

    public $to_gregorianDate;

    public $gregorianDate_fromDate;

    public $gregorianDate_toDate;

    public $catDischarge = [];

    public $show_data = false;

    public $catScale = [];

    public $isDataLoaded = false;

    public $modalTitle = '';

    public $modalMessage = '';


    public function loadTableData()
    {
        $this->isDataLoaded = true;
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'desc' ? 'asc' : 'desc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'desc';
        }
        $this->tableData();
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
        return Jalalian::fromCarbon($gregorianDate)->format('Y-m-d');
    }


    public function searchDataInDate()
    {

        $this->validate([
            'from_date' => 'required',
            'to_date' => 'required',
        ]);

        $this->gregorianDate_fromDate = Jalalian::fromFormat('Y-m-d', $this->from_date)
            ->toCarbon()
            ->toDateString();

        $this->gregorianDate_toDate = Jalalian::fromFormat('Y-m-d', $this->to_date)
            ->toCarbon()
            ->toDateString();
    }

    public function tableData()
    {
        if (!empty($this->from_date)) {
            $this->from_gregorianDate = $this->shamsiToGregorian($this->from_date);
        }

        if (!empty($this->to_date)) {
            $this->to_gregorianDate = $this->shamsiToGregorian($this->to_date);
        }
        $this->show_data = true;

        $data =
            Weight::with(['scale', 'customer', 'minral', 'purchase'])
            ->when(auth()->user()->position != 1, function ($query) {
                $query->where('provinces.province_code', auth()->user()->province_id);
            })
            ->when($this->search !== '', function ($query) {
                $query->where('weight.bill_id', '=', $this->search);
            })
            ->when($this->from_date !== null && $this->to_date !== null, function ($query) {
                $query->whereBetween('weight.created_at', [$this->from_gregorianDate, $this->to_gregorianDate]);
            })
            ->when($this->from_date !== null && $this->to_date == null, function ($query) {
                $query->whereBetween('weight.created_at', [$this->from_gregorianDate, now()]);
            })
            ->when($this->catProvince && count($this->catProvince) > 0, function ($query) {
                $query->whereIn('provinces.province_code', $this->catProvince);
            })
            ->when($this->catScale && count($this->catScale) > 0, function ($query) {
                $query->whereIn('weight.scale_id', $this->catScale);
            })
            ->when($this->catMineral && count($this->catMineral) > 0, function ($query) {
                $query->whereIn('weight.mineral_id', $this->catMineral);
            })
            ->leftJoin('minrals', 'weight.mineral_id', '=', 'minrals.id')
            ->leftJoin('purchase', 'weight.purchase_id', '=', 'purchase.id')
            ->leftJoin('customers', 'purchase.customer_id', '=', 'customers.id')
            ->leftJoin('scale', 'weight.scale_id', '=', 'scale.id')
            ->leftJoin('provinces', 'scale.department_id', '=', 'provinces.province_code')
            ->select(
                'customers.name AS customer_name',
                'minrals.minral_name',
                'scale.name AS scale_name',
                'provinces.name AS province_name',
                'weight.unit_id AS unit_id',
                DB::raw('SUM(weight.mineral_net_weight) AS mineral_net_weight'),
                DB::raw('COUNT(weight.id) AS cars'),
                DB::raw('MIN(weight.created_at) AS from_date'),
                DB::raw('MAX(weight.created_at) AS to_date'),
                DB::raw('SUM(CASE WHEN weight.discharge_place = "CHECKED" THEN 200 ELSE weight.mineral_net_weight * 10 END) AS Total_Revenue')
            )
            ->groupBy('customers.id', 'weight.mineral_id', 'weight.scale_id')
            ->orderBy($this->sortField, $this->sortDirection)
            ->simplePaginate($this->perPage ? $this->perPage : 10);



        $this->show_data = true;
        // $this->allWeights =  $data->toArray();

        return $data;
    }

    public function exportPdf($print)
    {
        $weights = Weight::with(['scale', 'customer', 'minral', 'purchase'])
            ->when(auth()->user()->position != 1, function ($query) {
                $query->where('provinces.province_code', auth()->user()->province_id);
            })
            ->when($this->search !== '', function ($query) {
                $query->where('weight.bill_id', '=', $this->search);
            })
            ->when($this->from_date !== null && $this->to_date !== null, function ($query) {
                $query->whereBetween('weight.created_at', [$this->from_gregorianDate, $this->to_gregorianDate]);
            })
            ->when($this->from_date !== null && $this->to_date == null, function ($query) {
                $query->whereBetween('weight.created_at', [$this->from_gregorianDate, now()]);
            })
            ->when($this->catProvince && count($this->catProvince) > 0, function ($query) {
                $query->whereIn('provinces.province_code', $this->catProvince);
            })
            ->when($this->catScale && count($this->catScale) > 0, function ($query) {
                $query->whereIn('weight.scale_id', $this->catScale);
            })
            ->when($this->catMineral && count($this->catMineral) > 0, function ($query) {
                $query->whereIn('weight.mineral_id', $this->catMineral);
            })
            ->leftJoin('minrals', 'weight.mineral_id', '=', 'minrals.id')
            ->leftJoin('purchase', 'weight.purchase_id', '=', 'purchase.id')
            ->leftJoin('customers', 'purchase.customer_id', '=', 'customers.id')
            ->leftJoin('scale', 'weight.scale_id', '=', 'scale.id')
            ->leftJoin('provinces', 'scale.department_id', '=', 'provinces.province_code')
            ->select(
                'customers.name AS customer_name',
                'minrals.minral_name',
                'scale.name AS scale_name',
                'provinces.name AS province_name',
                DB::raw('SUM(weight.mineral_net_weight) AS mineral_net_weight'),
                DB::raw('COUNT(weight.id) AS cars'),
                DB::raw('MIN(weight.created_at) AS from_date'),
                DB::raw('MAX(weight.created_at) AS to_date'),
                DB::raw('SUM(CASE WHEN weight.discharge_place = "CHECKED" THEN 200 ELSE weight.mineral_net_weight * 10 END) AS Total_Revenue')
            )
            ->groupBy('customers.id', 'weight.mineral_id', 'weight.scale_id')
            ->orderByDesc('weight.date')
            ->get();

        session()->put('export_weights_data', $weights);

        if ($print == 'pdf') {

            $this->dispatch('redirectToDownload', route('pdf.export'));
        } elseif ($print == 'print') {

            $this->dispatch('redirectToDownload', route('print.export'));
        }
    }

    public function exportToExcel()
    {
        $data = Weight::with(['scale', 'customer', 'minral', 'purchase'])
            ->when(auth()->user()->position != 1, function ($query) {
                $query->where('provinces.province_code', auth()->user()->province_id);
            })
            ->when($this->search !== '', function ($query) {
                $query->where('weight.bill_id', '=', $this->search);
            })
            ->when($this->from_date !== null && $this->to_date !== null, function ($query) {
                $query->whereBetween('weight.created_at', [$this->from_gregorianDate, $this->to_gregorianDate]);
            })
            ->when($this->from_date !== null && $this->to_date == null, function ($query) {
                $query->whereBetween('weight.created_at', [$this->from_gregorianDate, now()]);
            })
            ->when($this->catProvince && count($this->catProvince) > 0, function ($query) {
                $query->whereIn('provinces.province_code', $this->catProvince);
            })
            ->when($this->catScale && count($this->catScale) > 0, function ($query) {
                $query->whereIn('weight.scale_id', $this->catScale);
            })
            ->when($this->catMineral && count($this->catMineral) > 0, function ($query) {
                $query->whereIn('weight.mineral_id', $this->catMineral);
            })
            ->leftJoin('minrals', 'weight.mineral_id', '=', 'minrals.id')
            ->leftJoin('purchase', 'weight.purchase_id', '=', 'purchase.id')
            ->leftJoin('customers', 'purchase.customer_id', '=', 'customers.id')
            ->leftJoin('scale', 'weight.scale_id', '=', 'scale.id')
            ->leftJoin('provinces', 'scale.department_id', '=', 'provinces.province_code')
            ->select(
                'customers.name AS customer_name',
                'minrals.minral_name',
                'scale.name AS scale_name',
                'provinces.name AS province_name',
                DB::raw('SUM(weight.mineral_net_weight) AS mineral_net_weight'),
                DB::raw('COUNT(weight.id) AS cars'),
                DB::raw('MIN(weight.created_at) AS from_date'),
                DB::raw('MAX(weight.created_at) AS to_date'),
                DB::raw('SUM(CASE WHEN weight.discharge_place = "CHECKED" THEN 200 ELSE weight.mineral_net_weight * 10 END) AS Total_Revenue')
            )
            ->groupBy('customers.id', 'weight.mineral_id', 'weight.scale_id')
            ->orderByDesc('weight.date')
            ->get();

        return Excel::download(new WeightExport($data), 'weights.xlsx');
    }


    public function render()
    {

        return view('livewire.highscale.high-scale-report', [
            'table_data' => $this->isDataLoaded ? $this->tableData() : collect(),
            'scales' => DB::connection('scale_system')->table('scale')
                ->when(auth()->user()->position != 1, function ($query) {
                    $query->where('scale.department_id', auth()->user()->province_id);
                })
                ->get(),
            'minerals' => Mineral::all(),
            'provinces' => DB::connection('scale_system')->table('provinces')
                ->when(auth()->user()->position != 1, function ($query) {
                    $query->where('province_code', auth()->user()->province_id);
                })->get(),
            'units' => DB::connection('scale_system')->table('unit_category_details')->select('id', 'name')->get(),

        ]);
    }
}

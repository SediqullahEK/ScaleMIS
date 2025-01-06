<?php

namespace App\Livewire\Highscale;

use Livewire\Component;

use App\Models\ScaleMap;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AddScale extends Component
{
    use WithPagination;

    use WithFileUploads;

    public $scale_name = '';

    public $perPage = 10;

    public $scaleId = 0;

    public $isEditing = false;

    public $existing_image_path = '';

    public $isOpen = false;

    public $submit_data;

    public $edit_scale_name;

    public $purchase_date;

    public $s;

    public $edit = false;

    public $province = 0;

    public $location;

    public $latitude;

    public $longitude;

    public $scale_employee;

    public $employee_phone;

    public $status = 0;

    public $company;

    public $scale_image = '';

    public $description;



    // public function scaleSelect()
    // {
    //     /** @var object $scale */
    //     $scale = DB::connection('scale_system')->table('scale')->find($this->scale);

    //     $province = DB::connection('scale_system')->table('provinces')
    //         ->where('province_code', $scale->department_id)
    //         ->first();
    //     $this->province = $province->id;
    // }

    public function editScale($id)
    {
        $this->isEditing = true;
        $this->resetForm();

        $scale = DB::connection('scale_system')->table('scale_map')->find($id);

        $this->status = $scale->status;
        $this->company = $scale->scale_company;
        $this->description = $scale->description;
        // $this->s = DB::connection('scale_system')->table('scale')->find($scale->scale_id);

        $this->scale_name = $scale->scale_name;
        $this->province = $scale->province_id;
        $this->location = $scale->location;
        $this->longitude = $scale->longitude;
        $this->latitude = $scale->latitude;
        $this->scale_employee = $scale->scale_employee;
        $this->existing_image_path = $scale->scale_image;
        $this->employee_phone = $scale->employee_phone;
        $this->scaleId = $scale->id;
        $this->isOpen = true;
    }

    public function updateScale()
    {
        $validatedData = $this->validate([
            'scale_name' => 'required',
            'province' => 'required',
            'location' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'scale_employee' => 'required',
            'scale_image' => 'nullable|image|max:1024',

        ]);
        $scale = ScaleMap::find($this->scaleId);

        if ($this->scale_image) {
            if ($scale->scale_image) {
                Storage::disk('public')->delete($scale->scale_image);
            }
            try {
                $imageName = $scale->scale_id . time() . '.' . $this->scale_image->getClientOriginalExtension();
                $imagePath = $this->scale_image->storeAs('scale_images', $imageName, 'public');
                $scale->scale_image = $imagePath;
            } catch (\Exception $e) {
                dd($e->getMessage());
            }
        }

        $scale->scale_name = $this->scale_name;
        $scale->status = $this->status;
        $scale->province_id = $this->province;
        $scale->scale_company = $this->company;
        $scale->description = $this->description;
        $scale->location = $this->location;
        $scale->longitude = $this->longitude;
        $scale->latitude = $this->latitude;
        $scale->scale_employee = $this->scale_employee;
        $scale->employee_phone = $this->employee_phone;
        $done = $scale->save();

        if ($done) {
            $this->resetForm();
            $this->dispatch('recordUpdate');
        }
    }

    public function addScale()
    {
        $validatedData = $this->validate([
            'scale_name' => 'required',
            'province' => 'required',
            'location' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'scale_employee' => 'required',
            'scale_image' => 'nullable|image|max:1024',


        ]);
        $imagePath = '';

        if ($this->scale_image != '') {
            try {
                $imageName = $this->scale . time() . '.' . $this->scale_image->getClientOriginalExtension();
                $imagePath = $this->scale_image->storeAs('scale_images', $imageName, 'public');
                // dd($imagePath);
            } catch (\Exception $e) {
                dd($e->getMessage());  // Catch any errors
            }
        }
        // dd($validatedData);
        $scale = ScaleMap::create([
            'status' => $this->status,
            'scale_name' => $this->scale_name,
            'scale_company' => $this->company,
            'description' => $this->description,
            'scale_model' => "YAOHUA (XK3190-A9)",
            // 'scale_id' => $this->scale,
            'province_id' => $this->province,
            'scale_image' => $imagePath ?? null,
            'location' => $this->location,
            'longitude' => $this->longitude,
            'latitude' => $this->latitude,
            'scale_employee' => $this->scale_employee,
            'employee_phone' => $this->employee_phone,
        ]);

        if ($scale) {
            $this->resetForm();
            $this->dispatch('recordCreate');
        }
    }


    public function resetForm()
    {
        $this->scale_name = '';
        $this->province = 0;
        $this->status = 0;
        $this->location = '';
        $this->scale_image = null;
        $this->existing_image_path = '';
        $this->latitude = '';
        $this->longitude = '';
        $this->scale_employee = '';
        $this->employee_phone = '';
        $this->company = '';
        $this->description = '';
    }
    public function openForm($fs)
    {
        if ($fs) {
            $this->isEditing = false;
            $this->isOpen = true;
        } else {
            $this->isEditing = true;
            $this->isOpen = true;
        }
    }
    public function render()
    {
        return view('livewire.highscale.add-scale', [
            'table_data' => DB::connection('scale_system')->table('scale_map')
                // ->select(
                //     "scale_map.id",
                //     'scale_map.province_id',
                //     // 'scale.name as scale_name',
                //     'scale_map.scale_company',
                //     'scale_map.location',
                //     'scale_map.status',
                //     'scale_map.scale_employee',
                //     'scale_map.employee_phone',
                //     'scale_map.description'
                // )
                // ->join('scale', 'scale.id', '=', 'scale_map.scale_id')
                ->paginate($this->perPage),
            // 'scales' => DB::connection('scale_system')->table('scale')
            //     ->select(
            //         'scale.name as name',
            //         'scale.id as id'
            //     )
            //     ->leftjoin('scale_map', 'scale_map.scale_id', '=', 'scale.id')
            //     ->whereNull('scale_map.scale_id')
            //     ->distinct()
            //     ->get(),
            'provinces' => DB::connection('scale_system')->table('provinces')
                ->select(
                    'name',
                    'province_code',
                    'id'
                )
                ->whereNotIn('province_code', [90, 100]) // Correct method for excluding multiple IDs
                ->distinct()
                ->get(),

        ]);
    }
}

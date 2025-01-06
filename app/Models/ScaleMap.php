<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Scale;
use App\Models\Province;

class ScaleMap extends Model
{
    use HasFactory;

    protected $table = 'scale_map';

    protected $fillable = [
        'province_id',
        'scale_id',
        'scale_company',
        'location',
        'status',
        'scale_employee',
        'employee_phone',
        'description',
        'latitude',
        'longitude',
        'scale_model',
        'scale_image',
        'scale_name',

    ];

    public $timestamps = false;
    public function scale()
    {
        return $this->belongsTo(Scale::class, 'scale_id');
    }
    public function province()
    {
        return $this->belongsTo(Province::class, 'province_id');
    }
}

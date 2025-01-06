<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Province;

class Scale extends Model
{
    use HasFactory;
    protected $connection = "scale_system";
    protected $table = "scale";

    public function province()  
    {
        return $this->belongsTo(Province::class, 'department_id');  // Assuming 'province_id' is the foreign key in the 'weight' table
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Customer;

class Purchase extends Model
{
    use HasFactory;
    protected $connection = "scale_system";
    protected $table = "purchase";

    public function customer()  
    {
        return $this->belongsTo(Customer::class, 'customer_id');  // Assuming 'province_id' is the foreign key in the 'weight' table
    }
}

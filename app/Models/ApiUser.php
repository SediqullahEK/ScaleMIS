<?php

namespace App\Models;

use App\Models\Department;
use App\Models\Revenue\Bank_account;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ApiUser extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $table = "rmis_api_users";

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function department(){
        return $this->belongsTo(Department::class);
    }

    public function province(){
        return $this->belongsTo(Province::class);
    }


    public function bank_account(){
        return $this->belongsTo(Bank_account::class);
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    protected $connection = "mysql2";
    protected $table = "permissions";

    public function users(){
        return $this->belongsToMany(User::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $connection = 'mysql2';
    protected $table = 'employees';

    public function user(){
        
        return $this->hasOne(User::class);
    }

    public function manager(){
        return $this->belongsTo($this::class,'manager_id');
    }

    public function department(){
        return $this->belongsTo(Department::class);
    }

    public function position(){
        return $this->belongsTo(Position::class);
    }
}

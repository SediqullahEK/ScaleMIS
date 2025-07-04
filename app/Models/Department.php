<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $connection = 'mysql2';
    protected $table = 'departments';

    public function employee(){
        return $this->hasOne(Employee::class);
    }
}

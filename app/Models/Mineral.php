<?php

namespace App\Models;

use App\Models\Revenue\Revenue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Mineral extends Model
{
    use HasFactory;
    protected $connection = 'scale_system';
    protected $table = 'minrals';

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Scale;
use App\Models\Purchase;
use App\Models\Mineral;


class Weight extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $connection = "scale_system";
    protected $table = "weight";

     // Define the relationship to Province
    public function province()
    {
        return $this->belongsToThrough(Province::class, ScaleMap::class);
    }

    // Other relationships
    public function scale()
    {
        return $this->belongsTo(Scale::class);
    }

    public function customer()
    {
        return $this->belongsTo(Purchase::class);
    }

    public function minral()
    {
        return $this->belongsTo(Mineral::class, 'mineral_id');
    }

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }
}

<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $connection = "scale_system";
    protected $table = "users";

    protected $fillable = [
        'full_name',
        'user_name',
        'password',
        'email',
        'position',
        'province_id',
        'created_by',
        'updated_by',

    ];

    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }

    public function apiuser()
    {
        return $this->hasOne(ApiUser::class);
    }
}

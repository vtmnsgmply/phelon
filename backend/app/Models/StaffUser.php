<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

final class StaffUser extends Authenticatable
{
    protected $table = 'phelon_staff_users';
    protected $authPasswordName = 'password_hash';
    protected $fillable = ['email', 'display_name', 'password_hash', 'active'];
    protected $hidden = ['password_hash'];
    protected $casts = ['active' => 'boolean'];
    public $timestamps = false;
}

<?php

namespace App\Models;

use Couchbase\Role;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'password', 'user_role_id'];

    public function userRole()
    {
        return $this->belongsTo(UserRole::class);
    }

    public function isAdmin()
    {
        return $this->userRole()->where('name', 'admin')->exists();
    }
}

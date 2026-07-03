<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

// Extends Authenticatable (not the plain Eloquent Model) so this
// class works with Laravel's built-in Auth facade — Auth::attempt(),
// Auth::user(), the 'auth' middleware, etc. all expect this base class.
class User extends Authenticatable
{
    protected $table = 'users';
    protected $primaryKey = 'user_id';
    public $timestamps = false; // we manage created_at manually via DB default

    protected $fillable = [
        'role_id',
        'full_name',
        'email',
        'password',
        'phone',
        'status',
    ];

    protected $hidden = ['password'];

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'role_id');
    }

    public function cases()
    {
        return $this->hasMany(CaseModel::class, 'officer_id', 'user_id');
    }
}
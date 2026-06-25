<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'user_id';
    public $timestamps = false; 

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

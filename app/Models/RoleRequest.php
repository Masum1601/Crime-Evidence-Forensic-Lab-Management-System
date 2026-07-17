<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoleRequest extends Model
{
    protected $table = 'role_requests';
    protected $primaryKey = 'request_id';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'requested_role_id',
        'status',
        'reason',
        'reviewed_by',
        'reviewed_at',
        'review_notes',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function requestedRole()
    {
        return $this->belongsTo(Role::class, 'requested_role_id', 'role_id');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by', 'user_id');
    }
}
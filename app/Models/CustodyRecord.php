<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustodyRecord extends Model
{
    protected $table = 'custody_records';
    protected $primaryKey = 'custody_id';
    public $timestamps = false;

    protected $fillable = [
        'evidence_id',
        'from_user_id',
        'to_user_id',
        'transferred_by',
        'reason',
        'remarks',
    ];

    public function evidence()
    {
        return $this->belongsTo(Evidence::class, 'evidence_id', 'evidence_id');
    }

    public function fromUser()
    {
        return $this->belongsTo(User::class, 'from_user_id', 'user_id');
    }

    public function toUser()
    {
        return $this->belongsTo(User::class, 'to_user_id', 'user_id');
    }

    public function transferredByUser()
    {
        return $this->belongsTo(User::class, 'transferred_by', 'user_id');
    }
}
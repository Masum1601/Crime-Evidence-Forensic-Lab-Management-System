<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TestRequest extends Model
{
    protected $table = 'test_requests';
    protected $primaryKey = 'request_id';
    public $timestamps = false;

    protected $fillable = [
        'evidence_id',
        'test_type_id',
        'requested_by',
        'assigned_analyst_id',
        'test_status',
        'priority',
        'notes',
    ];

    public function evidence()
    {
        return $this->belongsTo(Evidence::class, 'evidence_id', 'evidence_id');
    }

    public function testType()
    {
        return $this->belongsTo(ForensicTestType::class, 'test_type_id', 'test_type_id');
    }

    public function requestedBy()
    {
        return $this->belongsTo(User::class, 'requested_by', 'user_id');
    }

    public function analyst()
    {
        return $this->belongsTo(User::class, 'assigned_analyst_id', 'user_id');
    }
}
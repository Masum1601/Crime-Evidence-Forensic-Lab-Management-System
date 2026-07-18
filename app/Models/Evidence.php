<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evidence extends Model
{
    protected $table = 'evidence';
    protected $primaryKey = 'evidence_id';
    public $timestamps = false;

    protected $fillable = [
        'case_id',
        'collected_by',
        'location_id',
        'evidence_name',
        'evidence_type',
        'description',
        'collection_date',
        'current_status',
        'barcode_no',
    ];

    public function case()
    {
        return $this->belongsTo(CaseModel::class, 'case_id', 'case_id');
    }

    public function collector()
    {
        return $this->belongsTo(User::class, 'collected_by', 'user_id');
    }

    public function location()
    {
        return $this->belongsTo(StorageLocation::class, 'location_id', 'location_id');
    }

    public function custodyRecords()
    {
        return $this->hasMany(CustodyRecord::class, 'evidence_id', 'evidence_id')
            ->orderBy('transfer_date', 'desc');
    }

    public function testRequests()
    {
        return $this->hasMany(TestRequest::class, 'evidence_id', 'evidence_id');
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EquipmentUsage extends Model
{
    protected $table = 'equipment_usage';
    protected $primaryKey = 'usage_id';
    public $timestamps = false;

    protected $fillable = [
        'equipment_id',
        'request_id',
        'used_by',
        'usage_date',
        'remarks',
    ];

    public function equipment()
    {
        return $this->belongsTo(Equipment::class, 'equipment_id', 'equipment_id');
    }

    public function testRequest()
    {
        return $this->belongsTo(TestRequest::class, 'request_id', 'request_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'used_by', 'user_id');
    }
}

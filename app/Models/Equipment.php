<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    protected $table = 'equipment';
    protected $primaryKey = 'equipment_id';
    public $timestamps = false;

    protected $fillable = ['equipment_name', 'equipment_type', 'serial_no', 'condition_status', 'availability_status'];

    public function usageLogs()
    {
        return $this->hasMany(EquipmentUsage::class, 'equipment_id', 'equipment_id');
    }
}
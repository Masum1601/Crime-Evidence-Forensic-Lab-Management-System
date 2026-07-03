<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StorageLocation extends Model
{
    protected $table = 'storage_locations';
    protected $primaryKey = 'location_id';
    public $timestamps = false;

    protected $fillable = [
        'location_name',
        'room_no',
        'shelf_no',
        'locker_no',
        'description',
    ];

    public function evidenceItems()
    {
        return $this->hasMany(Evidence::class, 'location_id', 'location_id');
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ForensicTestType extends Model
{
    protected $table = 'forensic_test_types';
    protected $primaryKey = 'test_type_id';
    public $timestamps = false;

    protected $fillable = [
        'test_name',
        'description',
        'estimated_duration',
    ];

    public function testRequests()
    {
        return $this->hasMany(TestRequest::class, 'test_type_id', 'test_type_id');
    }
}

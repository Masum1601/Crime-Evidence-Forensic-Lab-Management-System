<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// Note: named "CaseModel" instead of "Case" because `case` is a
// reserved keyword in PHP and cannot be used as a class name.
class CaseModel extends Model
{
    protected $table = 'cases';
    protected $primaryKey = 'case_id';
    public $timestamps = false;

    protected $fillable = [
        'case_title',
        'case_type',
        'case_description',
        'case_status',
        'opened_date',
        'closed_date',
        'officer_id',
    ];

    public function officer()
    {
        return $this->belongsTo(User::class, 'officer_id', 'user_id');
    }

    public function evidence()
    {
        return $this->hasMany(Evidence::class, 'case_id', 'case_id');
    }
}

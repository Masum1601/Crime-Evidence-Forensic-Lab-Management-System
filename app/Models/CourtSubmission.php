<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourtSubmission extends Model
{
    protected $table = 'court_submissions';
    protected $primaryKey = 'submission_id';
    public $timestamps = false;

    protected $fillable = [
        'evidence_id', 'submitted_by', 'court_name',
        'case_reference_no', 'return_date', 'status', 'remarks',
    ];

    public function evidence()
    {
        return $this->belongsTo(Evidence::class, 'evidence_id', 'evidence_id');
    }

    public function submittedByUser()
    {
        return $this->belongsTo(User::class, 'submitted_by', 'user_id');
    }
}
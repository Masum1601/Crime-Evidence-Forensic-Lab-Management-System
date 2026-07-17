<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PublicSubmission extends Model
{
    protected $table = 'public_submissions';
    protected $primaryKey = 'submission_id';
    public $timestamps = false;

    protected $fillable = [
        'submitter_name',
        'submitter_email',
        'submitter_phone',
        'subject',
        'description',
        'related_case_id',
        'status',
        'reviewed_by',
        'review_notes',
    ];

    public function relatedCase()
    {
        return $this->belongsTo(CaseModel::class, 'related_case_id', 'case_id');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by', 'user_id');
    }

    public function submittedByUser()
    {
        return $this->belongsTo(User::class, 'submitted_by', 'user_id');
    }
}
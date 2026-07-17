<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TestReport extends Model
{
    protected $table = 'test_reports';
    protected $primaryKey = 'report_id';
    public $timestamps = false;

    protected $fillable = ['request_id', 'result_summary', 'detailed_report', 'verified_by'];

    public function testRequest()
    {
        return $this->belongsTo(TestRequest::class, 'request_id', 'request_id');
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by', 'user_id');
    }
}
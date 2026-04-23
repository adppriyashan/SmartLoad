<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guarantor extends Model
{
    use HasFactory;

    protected $fillable = [
        'loan_request_id',
        'name',
        'nic',
        'address',
        'age',
        'job_title',
        'nic_copy',
        'salary_slips'
    ];

    protected $casts = [
        'salary_slips' => 'json',
    ];

    public function loanRequest()
    {
        return $this->belongsTo(LoanRequest::class);
    }
}

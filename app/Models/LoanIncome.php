<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanIncome extends Model
{
    use HasFactory;

    protected $fillable = ['loan_request_id', 'name', 'amount'];

    public function loanRequest()
    {
        return $this->belongsTo(LoanRequest::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanExpense extends Model
{
    protected $fillable = ['loan_request_id', 'name', 'amount', 'added_by_admin'];

    public function loanRequest()
    {
        return $this->belongsTo(LoanRequest::class);
    }
}

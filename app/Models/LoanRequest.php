<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'loan_type',
        'loan_amount',
        'loan_tenure',
        'employment_status',
        'basic_salary',
        'gross_salary',
        'active_loans_count',
        'nic_copy',
        'salary_slips',
        'bank_statement',
        'employment_letter',
        'optional_uploads',
        'status'
    ];

    protected $casts = [
        'salary_slips' => 'json',
        'optional_uploads' => 'json',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function guarantors()
    {
        return $this->hasMany(Guarantor::class);
    }

    public function incomes()
    {
        return $this->hasMany(LoanIncome::class);
    }

    public function commitments()
    {
        return $this->hasMany(LoanCommitment::class);
    }

    public function expenses()
    {
        return $this->hasMany(LoanExpense::class);
    }
}

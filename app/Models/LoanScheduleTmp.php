<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanScheduleTmp extends Model
{
    protected $table = 'loan_schedule_tmps';

    protected $primaryKey = 'tmp_loan_schedule_id';

    public $timestamps = false;

    protected $fillable = [
        'contract_no',
        'acct_id',
        'date_issue',
        'frequency_id',
        'next_pay_date',
        'tenor',
        'amount',
        'os_balance',
        'int_rate',
        'extra_rate',
        'interest_mode',
        'payment_mode',
        'savings',
        'credit_to_acct',
        'auto_pay_from_acct',
        'user_id',
        'approved_date',
        'approved_by',
        'purpose_id',
        'remark',
        'end_pay_date',
        'next_date',
        'gl_credit',
    ];

    protected $casts = [
        'date_issue' => 'date',
        'next_pay_date' => 'date',
        'approved_date' => 'date',
        'end_pay_date' => 'date',
        'next_date' => 'date',
        'amount' => 'decimal:5',
        'os_balance' => 'decimal:5',
        'int_rate' => 'decimal:2',
        'extra_rate' => 'decimal:2',
        'savings' => 'decimal:5',
    ];

    public function account()
    {
        return $this->belongsTo(AccountInfo::class, 'acct_id', 'acct_id');
    }

    public function frequency()
    {
        return $this->belongsTo(PaymentFrequency::class, 'frequency_id', 'frequency_id');
    }
}

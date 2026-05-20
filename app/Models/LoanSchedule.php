<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanSchedule extends Model
{
    protected $table = 'loan_schedules';
    protected $primaryKey = 'loan_schedule_id';
    public $timestamps = false;

    // Status Constants
    const STATUS_PENDING = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_CLOSED = 2;
    const STATUS_WRITTEN_OFF = 3;

    protected $fillable = [
        'contract_no',
        'acct_id',
        'date_issue',
        'frequency_id',
        'last_pay_date',
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
        'gl_credit'
    ];

    protected $casts = [
        'date_issue' => 'date',
        'last_pay_date' => 'date',
        'next_pay_date' => 'date',
        'approved_date' => 'date',
        'end_pay_date' => 'date',
        'next_date' => 'date',
        'amount' => 'decimal:2',
        'os_balance' => 'decimal:2',
        'int_rate' => 'decimal:2',
        'extra_rate' => 'decimal:2',
        'savings' => 'decimal:2'
    ];

    public function account()
    {
        return $this->belongsTo(AccountInfo::class, 'acct_id', 'acct_id');
    }

    public function frequency()
    {
        return $this->belongsTo(PaymentFrequency::class, 'frequency_id', 'frequency_id');
    }

    public function purpose()
    {
        return $this->belongsTo(PurposeLoan::class, 'purpose_id', 'purpose_id');
    }

    public function collaterals()
    {
        return $this->hasMany(Collateral::class, 'loan_schedule_id', 'loan_schedule_id');
    }

    public function transactions()
    {
        return $this->hasMany(LoanTran::class, 'loan_schedule_id', 'loan_schedule_id');
    }
}

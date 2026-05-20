<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanCustomScheduleTmp extends Model
{
    protected $table = 'loan_custom_schedule_tmps';

    protected $primaryKey = 'tmp_schedule_custom_id';

    public $timestamps = false;

    protected $fillable = [
        'loan_schedule_id',
        'savings',
        'int_pay_late',
    ];

    protected $casts = [
        'savings' => 'decimal:5',
    ];

    public function loanSchedule()
    {
        return $this->belongsTo(LoanSchedule::class, 'loan_schedule_id', 'loan_schedule_id');
    }

    public function details()
    {
        return $this->hasMany(LoanCustomScheduleDetailTmp::class, 'tmp_schedule_custom_id', 'tmp_schedule_custom_id');
    }
}

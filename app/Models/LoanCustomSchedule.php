<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanCustomSchedule extends Model
{
  protected $table = 'loan_custom_schedules';
  protected $primaryKey = 'schedule_custom_id';
  public $timestamps = false;

  const INT_PAY_LATE_FIXED = 1;
  const INT_PAY_LATE_AUTO = 2;

  protected $fillable = [
    'loan_schedule_id',
    'savings',
    'int_pay_late',
    'pay_status'
  ];

  protected $casts = [
    'savings' => 'decimal:5'
  ];

  public function loanSchedule()
  {
    return $this->belongsTo(LoanSchedule::class, 'loan_schedule_id', 'loan_schedule_id');
  }

  public function details()
  {
    return $this->hasMany(LoanCustomScheduleDetail::class, 'schedule_custom_id', 'schedule_custom_id');
  }
}

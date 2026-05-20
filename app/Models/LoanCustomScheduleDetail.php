<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanCustomScheduleDetail extends Model
{
  protected $table = 'loan_custom_schedule_details';
  protected $primaryKey = 'schedule_custom_detailt_id';
  public $timestamps = false;

  protected $fillable = [
    'schedule_custom_id',
    'pay_date',
    'principal',
    'interest',
    'pay_status'
  ];

  protected $casts = [
    'pay_date' => 'date',
    'principal' => 'decimal:5',
    'interest' => 'decimal:5'
  ];

  public function customSchedule()
  {
    return $this->belongsTo(LoanCustomSchedule::class, 'schedule_custom_id', 'schedule_custom_id');
  }
}

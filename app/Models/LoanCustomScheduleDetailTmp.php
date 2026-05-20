<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanCustomScheduleDetailTmp extends Model
{
    protected $table = 'loan_custom_schedule_detail_tmps';

    protected $primaryKey = 'tmp_schedule_custom_detailt_id';

    public $timestamps = false;

    protected $fillable = [
        'tmp_schedule_custom_id',
        'pay_date',
        'principal',
        'interest',
    ];

    protected $casts = [
        'pay_date' => 'date',
        'principal' => 'decimal:5',
        'interest' => 'decimal:5',
    ];

    public function customSchedule()
    {
        return $this->belongsTo(LoanCustomScheduleTmp::class, 'tmp_schedule_custom_id', 'tmp_schedule_custom_id');
    }
}

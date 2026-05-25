<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanArrear extends Model
{
    protected $table = 'loan_arears';

    protected $primaryKey = 'arrear_id';

    public $timestamps = false;

    protected $fillable = [
        'loan_schedule_id',
        'arrear_int',
        'arrear_prin',
        'arear_penalty',
        'arear_saving',
        'arrear_date',
    ];

    protected $casts = [
        'arrear_int' => 'decimal:5',
        'arrear_prin' => 'decimal:5',
        'arear_penalty' => 'decimal:5',
        'arear_saving' => 'decimal:5',
        'arrear_date' => 'date',
    ];

    public function loanSchedule()
    {
        return $this->belongsTo(LoanSchedule::class, 'loan_schedule_id', 'loan_schedule_id');
    }

    /**
     * Get total arrear amount
     */
    public function getTotalArrearAttribute()
    {
        return $this->arrear_int + $this->arrear_prin + $this->arear_penalty + $this->arear_saving;
    }
}

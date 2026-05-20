<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CollateralTmp extends Model
{
    protected $table = 'collateral_tmps';

    protected $primaryKey = 'tmp_collateral_id';

    public $timestamps = false;

    protected $fillable = [
        'loan_schedule_id',
        'collateral_type_id',
        'collateral_value',
        'collateral_no',
        'date_issue',
        'remarks',
    ];

    protected $casts = [
        'date_issue' => 'date',
    ];

    public function loanSchedule()
    {
        return $this->belongsTo(LoanSchedule::class, 'loan_schedule_id', 'loan_schedule_id');
    }

    public function collateralType()
    {
        return $this->belongsTo(CollateralType::class, 'collateral_type_id', 'collateral_type_id');
    }
}

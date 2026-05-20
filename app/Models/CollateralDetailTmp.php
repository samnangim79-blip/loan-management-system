<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CollateralDetailTmp extends Model
{
    protected $table = 'collateral_detail_tmps';

    protected $primaryKey = 'tmp_loan_col_detail_id';

    public $timestamps = false;

    protected $fillable = [
        'collateral_id',
        'col_detail_id',
        'col_value',
    ];

    public function collateral()
    {
        return $this->belongsTo(Collateral::class, 'collateral_id', 'collateral_id');
    }
}

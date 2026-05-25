<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CollateralReleaseTmp extends Model
{
    protected $table = 'collateral_release_tmps';

    protected $primaryKey = 'tmp_release_id';

    public $timestamps = false;

    protected $fillable = [
        'tran_date',
        'collateral_id',
        'release_by',
        'release_date',
        'approved_by',
        'approved_date',
    ];

    protected $casts = [
        'tran_date' => 'date',
        'release_date' => 'datetime',
        'approved_date' => 'datetime',
    ];

    public function collateral()
    {
        return $this->belongsTo(Collateral::class, 'collateral_id', 'collateral_id');
    }
}

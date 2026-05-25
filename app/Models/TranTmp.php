<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TranTmp extends Model
{
    protected $table = 'tran_tmps';

    protected $primaryKey = 'tmp_tran_id';

    public $timestamps = false;

    protected $fillable = [
        'branch_id',
        'tran_date',
        'gl_map_id',
        'amount',
        'ccy_id',
        'discription',
        'user_id',
        'done_date',
        'approved_by',
        'is_approve',
    ];

    protected $casts = [
        'tran_date' => 'date',
        'done_date' => 'datetime',
        'amount' => 'decimal:5',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'branch_id');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'ccy_id', 'ccy_id');
    }

    public function glMap()
    {
        return $this->belongsTo(GlMap::class, 'gl_map_id', 'gl_map_id');
    }
}

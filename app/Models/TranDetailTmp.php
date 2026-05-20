<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TranDetailTmp extends Model
{
    protected $table = 'tran_detail_tmps';

    protected $primaryKey = 'tmp_tran_detail_id';

    public $timestamps = false;

    protected $fillable = [
        'tran_id',
        'dr_cr',
        'gl_id',
        'balance',
    ];

    protected $casts = [
        'balance' => 'decimal:5',
    ];

    public function gl()
    {
        return $this->belongsTo(Gl::class, 'gl_id', 'gl_id');
    }
}

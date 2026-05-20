<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TranDetail extends Model
{
  protected $table = 'tran_details';
  protected $primaryKey = 'tran_detail_id';
  public $timestamps = false;

  protected $fillable = [
    'tran_id',
    'dr_cr',
    'gl_id',
    'balance'
  ];

  protected $casts = [
    'balance' => 'decimal:5'
  ];

  const DEBIT = 'd';
  const CREDIT = 'c';

  public function transaction()
  {
    return $this->belongsTo(Trans::class, 'tran_id', 'tran_id');
  }

  public function gl()
  {
    return $this->belongsTo(Gl::class, 'gl_id', 'gl_id');
  }
}

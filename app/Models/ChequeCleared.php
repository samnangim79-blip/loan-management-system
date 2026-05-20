<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChequeCleared extends Model
{
  protected $table = 'cheque_cleareds';
  public $timestamps = false;
  public $incrementing = false;

  protected $fillable = [
    'chq_id',
    'tran_id',
    'chq_status_id'
  ];

  public function transaction()
  {
    return $this->belongsTo(Trans::class, 'tran_id', 'tran_id');
  }

  public function status()
  {
    return $this->belongsTo(ChequeStatus::class, 'chq_status_id', 'chq_status_id');
  }
}

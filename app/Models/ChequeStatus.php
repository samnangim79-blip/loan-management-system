<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChequeStatus extends Model
{
  protected $table = 'cheque_statuss';
  protected $primaryKey = 'chq_status_id';
  public $timestamps = false;
  public $incrementing = false;

  protected $fillable = [
    'chq_status_id',
    'chq_status',
    're_presenting'
  ];

  public function clearedCheques()
  {
    return $this->hasMany(ChequeCleared::class, 'chq_status_id', 'chq_status_id');
  }
}

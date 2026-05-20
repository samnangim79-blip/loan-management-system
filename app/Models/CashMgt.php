<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CashMgt extends Model
{
  protected $table = 'cash_mgts';
  protected $primaryKey = 'cash_mgt_id';
  public $timestamps = false;

  protected $fillable = [
    'tran_date',
    'amount',
    'in_out',
    'balance',
    'ccy_id',
    'user_id',
    'date_done',
    'remark'
  ];

  protected $casts = [
    'tran_date' => 'date',
    'date_done' => 'datetime',
    'amount' => 'decimal:5',
    'balance' => 'decimal:5'
  ];

  const IN = 'i';
  const OUT = 'o';

  public function currency()
  {
    return $this->belongsTo(Currency::class, 'ccy_id', 'ccy_id');
  }

  public function user()
  {
    return $this->belongsTo(UserLogin::class, 'user_id', 'user_id');
  }
}

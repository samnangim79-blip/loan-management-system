<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenddingCashTransfer extends Model
{
  protected $table = 'pendding_cash_transfers';
  protected $primaryKey = 'pendding_cash_transfer_id';
  public $timestamps = false;
  public $incrementing = false;

  const STATUS_PENDING = 0;
  const STATUS_RECEIPT = 1;

  protected $fillable = [
    'pendding_cash_transfer_id',
    'amount',
    'in_ou',
    'ccy_id',
    'user_id',
    'sent_date',
    'remark',
    'status_id'
  ];

  protected $casts = [
    'sent_date' => 'datetime',
    'amount' => 'decimal:5'
  ];

  public function currency()
  {
    return $this->belongsTo(Currency::class, 'ccy_id', 'ccy_id');
  }

  public function user()
  {
    return $this->belongsTo(UserLogin::class, 'user_id', 'user_id');
  }
}

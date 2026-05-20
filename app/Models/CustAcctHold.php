<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustAcctHold extends Model
{
  protected $table = 'cust_acct_holds';
  protected $primaryKey = 'acct_hold_id';
  public $timestamps = false;
  public $incrementing = false;

  protected $fillable = [
    'acct_hold_id',
    'acct_id',
    'hold_amount',
    'description',
    'hold_date',
    'hold_by'
  ];

  protected $casts = [
    'hold_date' => 'datetime',
    'hold_amount' => 'decimal:5'
  ];

  public function account()
  {
    return $this->belongsTo(AccountInfo::class, 'acct_id', 'acct_id');
  }
}

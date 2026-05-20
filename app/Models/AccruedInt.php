<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccruedInt extends Model
{
  protected $table = 'accrued_ints';
  public $timestamps = false;
  public $incrementing = false;

  protected $fillable = [
    'acct_id',
    'last_accrued_int',
    'last_accrued_date',
    'accrued_int_balance'
  ];

  protected $casts = [
    'last_accrued_date' => 'date',
    'last_accrued_int' => 'decimal:5',
    'accrued_int_balance' => 'decimal:5'
  ];

  public function account()
  {
    return $this->belongsTo(AccountInfo::class, 'acct_id', 'acct_id');
  }
}

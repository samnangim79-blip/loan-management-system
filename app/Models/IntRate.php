<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IntRate extends Model
{
  protected $table = 'int_rates';
  protected $primaryKey = 'int_rate_id';
  public $timestamps = false;

  protected $fillable = [
    'rate',
    'acct_type_id'
  ];

  protected $casts = [
    'rate' => 'decimal:2'
  ];

  public function accountType()
  {
    return $this->belongsTo(AccountType::class, 'acct_type_id', 'acct_type_id');
  }
}

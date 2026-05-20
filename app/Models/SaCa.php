<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaCa extends Model
{
  protected $table = 'sa_cas';
  public $timestamps = false;
  public $incrementing = false;

  protected $fillable = [
    'acct_id',
    'extra_rate',
    'updated_by',
    'updated_date'
  ];

  protected $casts = [
    'updated_date' => 'date',
    'extra_rate' => 'decimal:2'
  ];

  public function account()
  {
    return $this->belongsTo(AccountInfo::class, 'acct_id', 'acct_id');
  }
}

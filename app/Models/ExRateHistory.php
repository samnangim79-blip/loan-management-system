<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExRateHistory extends Model
{
  protected $table = 'ex_rate_historys';
  protected $primaryKey = 'ex_rate_history_id';
  public $timestamps = false;
  public $incrementing = false;

  protected $fillable = [
    'ex_rate_history_id',
    'ex_rate',
    'rate_date'
  ];

  protected $casts = [
    'rate_date' => 'date',
    'ex_rate' => 'decimal:2'
  ];
}

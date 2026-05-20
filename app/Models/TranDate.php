<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TranDate extends Model
{
  protected $table = 'tran_dates';
  protected $primaryKey = 'tran_date';
  public $timestamps = false;
  public $incrementing = false;
  protected $keyType = 'string';

  protected $fillable = [
    'tran_date',
    'started_by',
    'started_date',
    'ended_by',
    'ended_date'
  ];

  protected $casts = [
    'started_date' => 'datetime',
    'ended_date' => 'datetime'
  ];
}

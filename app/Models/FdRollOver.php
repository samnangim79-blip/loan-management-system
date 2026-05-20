<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FdRollOver extends Model
{
  protected $table = 'fd_roll_overs';
  protected $primaryKey = 'roll_over_id';
  public $timestamps = false;
  public $incrementing = false;

  protected $fillable = [
    'roll_over_id',
    'fd_cert_id',
    'roll_over_date',
    'matured_date',
    'amount',
    'int_rate'
  ];

  protected $casts = [
    'roll_over_date' => 'date',
    'matured_date' => 'date',
    'amount' => 'decimal:5',
    'int_rate' => 'decimal:2'
  ];

  public function fdCert()
  {
    return $this->belongsTo(FdCert::class, 'fd_cert_id', 'fd_cert_id');
  }
}

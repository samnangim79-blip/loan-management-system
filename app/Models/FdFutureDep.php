<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FdFutureDep extends Model
{
  protected $table = 'fd_future_deps';
  protected $primaryKey = 'fd_dep_id';
  public $timestamps = false;
  public $incrementing = false;

  protected $fillable = [
    'fd_dep_id',
    'fd_cert_id',
    'amount',
    'date_to_dep',
    'date_done'
  ];

  protected $casts = [
    'date_to_dep' => 'date',
    'date_done' => 'datetime',
    'amount' => 'decimal:5'
  ];

  public function fdCert()
  {
    return $this->belongsTo(FdCert::class, 'fd_cert_id', 'fd_cert_id');
  }
}

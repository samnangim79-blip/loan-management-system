<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FdTran extends Model
{
  protected $table = 'fd_trans';
  protected $primaryKey = 'fd_tran_id';
  public $timestamps = false;
  public $incrementing = false;

  protected $fillable = [
    'fd_tran_id',
    'fd_cert_id',
    'status'
  ];

  public function fdCert()
  {
    return $this->belongsTo(FdCert::class, 'fd_cert_id', 'fd_cert_id');
  }
}

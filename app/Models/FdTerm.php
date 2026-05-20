<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FdTerm extends Model
{
  protected $table = 'fd_terms';
  protected $primaryKey = 'fd_term_id';
  public $timestamps = false;
  public $incrementing = false;

  protected $fillable = [
    'fd_term_id',
    'term_name',
    'days_num',
    'int_rate',
    'grace_period',
    'break_term_fee'
  ];

  protected $casts = [
    'int_rate' => 'decimal:2',
    'break_term_fee' => 'decimal:2'
  ];

  public function fdCerts()
  {
    return $this->hasMany(FdCert::class, 'fd_term_id', 'fd_term_id');
  }
}

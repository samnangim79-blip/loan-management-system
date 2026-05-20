<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FdOption extends Model
{
  protected $table = 'fd_options';
  protected $primaryKey = 'fd_option_id';
  public $timestamps = false;
  public $incrementing = false;

  protected $fillable = [
    'fd_option_id',
    'fd_option'
  ];

  public function fdCerts()
  {
    return $this->hasMany(FdCert::class, 'fd_option_id', 'fd_option_id');
  }
}

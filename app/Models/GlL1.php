<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GlL1 extends Model
{
  protected $table = 'gl_l1s';
  protected $primaryKey = 'l1_id';
  public $timestamps = false;
  public $incrementing = false;

  protected $fillable = [
    'l1_id',
    'l1_desc',
    'drcr'
  ];

  public function level2s()
  {
    return $this->hasMany(GlL2::class, 'l1_id', 'l1_id');
  }
}

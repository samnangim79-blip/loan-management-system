<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GlL3 extends Model
{
  protected $table = 'gl_l3s';
  protected $primaryKey = 'l3_id';
  public $timestamps = false;
  public $incrementing = false;

  protected $fillable = [
    'l3_id',
    'l3_desc',
    'l2_id'
  ];

  public function level2()
  {
    return $this->belongsTo(GlL2::class, 'l2_id', 'l2_id');
  }

  public function level4s()
  {
    return $this->hasMany(GlL4::class, 'l3_id', 'l3_id');
  }
}

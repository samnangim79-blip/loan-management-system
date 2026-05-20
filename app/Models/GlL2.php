<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GlL2 extends Model
{
  protected $table = 'gl_l2s';
  protected $primaryKey = 'l2_id';
  public $timestamps = false;
  public $incrementing = false;

  protected $fillable = [
    'l2_id',
    'l2_desc',
    'l1_id'
  ];

  public function level1()
  {
    return $this->belongsTo(GlL1::class, 'l1_id', 'l1_id');
  }

  public function level3s()
  {
    return $this->hasMany(GlL3::class, 'l2_id', 'l2_id');
  }
}

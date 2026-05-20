<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GlL4 extends Model
{
  protected $table = 'gl_l4s';
  protected $primaryKey = 'l4_id';
  public $timestamps = false;
  public $incrementing = false;

  protected $fillable = [
    'l4_id',
    'l4_desc',
    'l3_id'
  ];

  public function level3()
  {
    return $this->belongsTo(GlL3::class, 'l3_id', 'l3_id');
  }

  public function gls()
  {
    return $this->hasMany(Gl::class, 'l4_id', 'l4_id');
  }
}

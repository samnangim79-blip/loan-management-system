<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gl extends Model
{
  protected $table = 'gls';
  protected $primaryKey = 'gl_id';
  public $timestamps = false;

  protected $fillable = [
    'gl_code',
    'gl_name',
    'gl_name_kh',
    'l4_id'
  ];

  public function level4()
  {
    return $this->belongsTo(GlL4::class, 'l4_id', 'l4_id');
  }
}

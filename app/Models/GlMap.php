<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GlMap extends Model
{
  protected $table = 'gl_maps';
  protected $primaryKey = 'gl_map_id';
  public $timestamps = false;

  protected $fillable = [
    'short_code',
    'tran_desc',
    'debit_gl_id',
    'credit_gl_id',
    'created_by'
  ];

  public function debitGl()
  {
    return $this->belongsTo(Gl::class, 'debit_gl_id', 'gl_id');
  }

  public function creditGl()
  {
    return $this->belongsTo(Gl::class, 'credit_gl_id', 'gl_id');
  }

  public function transactions()
  {
    return $this->hasMany(Trans::class, 'gl_map_id', 'gl_map_id');
  }
}

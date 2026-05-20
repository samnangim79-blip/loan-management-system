<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FixedAssetType extends Model
{
  protected $table = 'fixed_asset_types';
  protected $primaryKey = 'fa_type_id';
  public $timestamps = false;

  protected $fillable = [
    'fa_type',
    'gl_id',
    'depre_gl',
    'exp_gl',
    'dispose_gl'
  ];

  public function fixedAssets()
  {
    return $this->hasMany(FixedAsset::class, 'fa_type_id', 'fa_type_id');
  }

  public function gl()
  {
    return $this->belongsTo(Gl::class, 'gl_id', 'gl_id');
  }

  public function depreGl()
  {
    return $this->belongsTo(Gl::class, 'depre_gl', 'gl_id');
  }

  public function expGl()
  {
    return $this->belongsTo(Gl::class, 'exp_gl', 'gl_id');
  }

  public function disposeGl()
  {
    return $this->belongsTo(Gl::class, 'dispose_gl', 'gl_id');
  }
}

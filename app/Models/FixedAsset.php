<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FixedAsset extends Model
{
  protected $table = 'fixed_assets';
  protected $primaryKey = 'fa_id';
  public $timestamps = false;

  protected $fillable = [
    'fa_code',
    'fa_desc',
    'fa_comment',
    'fa_type_id',
    'purchase_date',
    'purchase_price',
    'ccy_id',
    'usefull_life',
    'net_value',
    'dispose_date',
    'dispose_value',
    'dispose_comment',
    'added_by',
    'added_date',
    'dispose_by',
    'credit_gl'
  ];

  protected $casts = [
    'purchase_date' => 'date',
    'dispose_date' => 'date',
    'added_date' => 'datetime',
    'purchase_price' => 'decimal:5',
    'net_value' => 'decimal:5',
    'dispose_value' => 'decimal:5'
  ];

  public function assetType()
  {
    return $this->belongsTo(FixedAssetType::class, 'fa_type_id', 'fa_type_id');
  }

  public function currency()
  {
    return $this->belongsTo(Currency::class, 'ccy_id', 'ccy_id');
  }

  public function addedBy()
  {
    return $this->belongsTo(UserLogin::class, 'added_by', 'user_id');
  }

  public function disposedBy()
  {
    return $this->belongsTo(UserLogin::class, 'dispose_by', 'user_id');
  }

  public function depreciations()
  {
    return $this->hasMany(FixedAssetDepre::class, 'fa_id', 'fa_id');
  }

  public function creditGl()
  {
    return $this->belongsTo(Gl::class, 'credit_gl', 'gl_id');
  }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssetType extends Model
{
  protected $table = 'asset_types';
  protected $primaryKey = 'asset_type_id';
  public $timestamps = false;

  protected $fillable = [
    'asset_type'
  ];

  public function customerAssets()
  {
    return $this->hasMany(CustAssetDetail::class, 'asset_type_id', 'asset_type_id');
  }
}

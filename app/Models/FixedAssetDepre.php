<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FixedAssetDepre extends Model
{
  protected $table = 'fixed_asset_depres';
  protected $primaryKey = 'depre_id';
  public $timestamps = false;

  protected $fillable = [
    'depre_date',
    'tran_id',
    'fa_id',
    'amount'
  ];

  protected $casts = [
    'depre_date' => 'date',
    'amount' => 'decimal:5'
  ];

  public function fixedAsset()
  {
    return $this->belongsTo(FixedAsset::class, 'fa_id', 'fa_id');
  }

  public function transaction()
  {
    return $this->belongsTo(Trans::class, 'tran_id', 'tran_id');
  }
}

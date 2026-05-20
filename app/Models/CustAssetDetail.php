<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustAssetDetail extends Model
{
  protected $table = 'cust_asset_details';
  protected $primaryKey = 'cust_asset_id';
  public $timestamps = false;

  protected $fillable = [
    'cust_income_id',
    'asset_type_id',
    'description',
    'estimated_value'
  ];

  protected $casts = [
    'estimated_value' => 'decimal:5'
  ];

  public function incomeHistory()
  {
    return $this->belongsTo(CustIncomeHistory::class, 'cust_income_id', 'cust_income_id');
  }

  public function assetType()
  {
    return $this->belongsTo(AssetType::class, 'asset_type_id', 'asset_type_id');
  }
}

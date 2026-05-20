<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CollateralTypeDetail extends Model
{
  protected $table = 'collateral_type_details';
  protected $primaryKey = 'collateral_detail_id';
  public $timestamps = false;

  protected $fillable = [
    'collateral_type_id',
    'description'
  ];

  public function collateralType()
  {
    return $this->belongsTo(CollateralType::class, 'collateral_type_id', 'collateral_type_id');
  }

  public function collateralDetails()
  {
    return $this->hasMany(CollateralDetail::class, 'col_detail_id', 'collateral_detail_id');
  }
}

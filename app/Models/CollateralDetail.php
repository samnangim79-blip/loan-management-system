<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CollateralDetail extends Model
{
  protected $table = 'collateral_details';
  protected $primaryKey = 'loan_col_detail_id';
  public $timestamps = false;

  protected $fillable = [
    'collateral_id',
    'col_detail_id',
    'col_value'
  ];

  public function collateral()
  {
    return $this->belongsTo(Collateral::class, 'collateral_id', 'collateral_id');
  }

  public function typeDetail()
  {
    return $this->belongsTo(CollateralTypeDetail::class, 'col_detail_id', 'collateral_detail_id');
  }
}

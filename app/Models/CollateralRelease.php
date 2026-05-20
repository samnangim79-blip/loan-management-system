<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CollateralRelease extends Model
{
  protected $table = 'collateral_releases';
  protected $primaryKey = 'release_id';
  public $timestamps = false;

  protected $fillable = [
    'tran_date',
    'collateral_id',
    'release_by',
    'release_date',
    'approved_by',
    'approved_date'
  ];

  protected $casts = [
    'tran_date' => 'date',
    'release_date' => 'date',
    'approved_date' => 'date'
  ];

  public function collateral()
  {
    return $this->belongsTo(Collateral::class, 'collateral_id', 'collateral_id');
  }

  public function releasedBy()
  {
    return $this->belongsTo(UserLogin::class, 'release_by', 'user_id');
  }

  public function approvedBy()
  {
    return $this->belongsTo(UserLogin::class, 'approved_by', 'user_id');
  }
}

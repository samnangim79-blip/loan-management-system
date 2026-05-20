<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OdLimit extends Model
{
  protected $table = 'od_limits';
  protected $primaryKey = 'od_limit_id';
  public $timestamps = false;
  public $incrementing = false;

  protected $fillable = [
    'od_limit_id',
    'acct_id',
    'limit_amt',
    'granted_date',
    'limit_remark',
    'user_id'
  ];

  protected $casts = [
    'granted_date' => 'date',
    'limit_amt' => 'decimal:5'
  ];

  public function account()
  {
    return $this->belongsTo(AccountInfo::class, 'acct_id', 'acct_id');
  }

  public function user()
  {
    return $this->belongsTo(UserLogin::class, 'user_id', 'user_id');
  }
}

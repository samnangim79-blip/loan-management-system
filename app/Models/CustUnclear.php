<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustUnclear extends Model
{
  protected $table = 'cust_unclears';
  protected $primaryKey = 'acct_unclear_id';
  public $timestamps = false;
  public $incrementing = false;

  protected $fillable = [
    'acct_unclear_id',
    'cust_tran_id',
    'description',
    'clear_by',
    'clear_date'
  ];

  protected $casts = [
    'clear_date' => 'datetime'
  ];

  public function custTransaction()
  {
    return $this->belongsTo(CustAcctTran::class, 'cust_tran_id', 'cust_tran_id');
  }
}

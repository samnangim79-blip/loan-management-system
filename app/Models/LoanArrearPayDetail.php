<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanArrearPayDetail extends Model
{
  protected $table = 'loan_arrear_pay_details';
  public $timestamps = false;
  public $incrementing = false;

  protected $fillable = [
    'tran_id',
    'arrear_id',
    'last_pay_arrear_date',
    'int_pay',
    'prin_pay',
    'pen_pay',
    'sav_pay'
  ];

  protected $casts = [
    'last_pay_arrear_date' => 'date',
    'int_pay' => 'decimal:5',
    'prin_pay' => 'decimal:5',
    'pen_pay' => 'decimal:5',
    'sav_pay' => 'decimal:5'
  ];

  public function transaction()
  {
    return $this->belongsTo(Trans::class, 'tran_id', 'tran_id');
  }

  public function loanArrear()
  {
    return $this->belongsTo(LoanArrear::class, 'arrear_id', 'arrear_id');
  }
}

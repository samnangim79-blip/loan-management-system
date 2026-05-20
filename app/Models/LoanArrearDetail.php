<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanArrearDetail extends Model
{
  protected $table = 'loan_arrear_details';
  protected $primaryKey = 'arrear_id';
  public $timestamps = false;
  public $incrementing = false;

  protected $fillable = [
    'arrear_id',
    'arrear_int',
    'arrear_prin',
    'arrear_pen',
    'arrear_sav',
    'arrear_date'
  ];

  protected $casts = [
    'arrear_date' => 'date',
    'arrear_int' => 'decimal:5',
    'arrear_prin' => 'decimal:5',
    'arrear_pen' => 'decimal:5',
    'arrear_sav' => 'decimal:5'
  ];

  public function loanArrear()
  {
    return $this->belongsTo(LoanArrear::class, 'arrear_id', 'arrear_id');
  }
}

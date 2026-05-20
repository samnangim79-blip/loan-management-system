<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanTranType extends Model
{
  protected $table = 'loan_tran_types';
  protected $primaryKey = 'loan_tran_type_id';
  public $timestamps = false;
  public $incrementing = false;

  protected $fillable = [
    'loan_tran_type_id',
    'loan_tran_type'
  ];

  public function loanTransactions()
  {
    return $this->hasMany(LoanTran::class, 'loan_tran_type_id', 'loan_tran_type_id');
  }
}

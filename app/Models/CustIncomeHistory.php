<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustIncomeHistory extends Model
{
  protected $table = 'cust_income_historys';
  protected $primaryKey = 'cust_income_id';
  public $timestamps = false;

  protected $fillable = [
    'cust_id',
    'income',
    'expense',
    'liability',
    'remark',
    'posted_date',
    'posted_by',
    'last_updated',
    'updated_by'
  ];

  protected $casts = [
    'income' => 'decimal:5',
    'expense' => 'decimal:5',
    'liability' => 'decimal:5',
    'posted_date' => 'datetime',
    'last_updated' => 'date'
  ];

  public function customer()
  {
    return $this->belongsTo(Customer::class, 'cust_id', 'cust_id');
  }

  public function assets()
  {
    return $this->hasMany(CustAssetDetail::class, 'cust_income_id', 'cust_income_id');
  }

  /**
   * Get net income (income - expense - liability)
   */
  public function getNetIncomeAttribute()
  {
    return $this->income - $this->expense - $this->liability;
  }
}

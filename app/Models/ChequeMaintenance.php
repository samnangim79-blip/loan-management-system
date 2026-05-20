<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChequeMaintenance extends Model
{
  protected $table = 'cheque_maintenances';
  protected $primaryKey = 'chq_id';
  public $timestamps = false;

  protected $fillable = [
    'tran_date',
    'branch_id',
    'qty',
    'chq_from_no',
    'chq_to_no',
    'main_by',
    'main_date',
    'approved_by',
    'approved_date'
  ];

  protected $casts = [
    'tran_date' => 'date',
    'main_date' => 'datetime',
    'approved_date' => 'datetime'
  ];

  public function branch()
  {
    return $this->belongsTo(Branch::class, 'branch_id', 'branch_id');
  }

  public function maintainedBy()
  {
    return $this->belongsTo(UserLogin::class, 'main_by', 'user_id');
  }

  public function approvedBy()
  {
    return $this->belongsTo(UserLogin::class, 'approved_by', 'user_id');
  }
}

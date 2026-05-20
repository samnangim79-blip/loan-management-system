<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BranchTran extends Model
{
  protected $table = 'branch_trans';
  protected $primaryKey = 'branch_tran_id';
  public $timestamps = false;

  protected $fillable = [
    'branch_id',
    'tran_date',
    'started_by',
    'started_date',
    'ended_by',
    'ended_date'
  ];

  protected $casts = [
    'tran_date' => 'date',
    'started_date' => 'datetime',
    'ended_date' => 'datetime'
  ];

  public function branch()
  {
    return $this->belongsTo(Branch::class, 'branch_id', 'branch_id');
  }

  public function startedBy()
  {
    return $this->belongsTo(UserLogin::class, 'started_by', 'user_id');
  }

  public function endedBy()
  {
    return $this->belongsTo(UserLogin::class, 'ended_by', 'user_id');
  }
}

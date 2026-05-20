<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupDetail extends Model
{
  protected $table = 'group_details';
  protected $primaryKey = 'group_detail_id';
  public $timestamps = false;

  protected $fillable = [
    'group_id',
    'contract_no'
  ];

  public function group()
  {
    return $this->belongsTo(Group::class, 'group_id', 'group_id');
  }

  public function loanSchedule()
  {
    return $this->belongsTo(LoanSchedule::class, 'contract_no', 'contract_no');
  }
}

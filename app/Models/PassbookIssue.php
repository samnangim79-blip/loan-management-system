<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PassbookIssue extends Model
{
  protected $table = 'passbook_issues';
  protected $primaryKey = 'pass_issue_id';
  public $timestamps = false;

  protected $fillable = [
    'acct_id',
    'passbook_no',
    'last_printed_page',
    'last_printed_line',
    'status',
    'issue_by',
    'issue_date',
    'approved_by',
    'approved_date'
  ];

  protected $casts = [
    'issue_date' => 'datetime',
    'approved_date' => 'datetime'
  ];

  public function account()
  {
    return $this->belongsTo(AccountInfo::class, 'acct_id', 'acct_id');
  }

  public function issuedBy()
  {
    return $this->belongsTo(UserLogin::class, 'issue_by', 'user_id');
  }

  public function approvedBy()
  {
    return $this->belongsTo(UserLogin::class, 'approved_by', 'user_id');
  }
}

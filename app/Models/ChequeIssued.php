<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChequeIssued extends Model
{
  protected $table = 'cheque_issueds';
  protected $primaryKey = 'chq_issued_id';
  public $timestamps = false;
  public $incrementing = false;

  protected $fillable = [
    'chq_issued_id',
    'acct_id',
    'chq_no_from',
    'chq_no_to',
    'issued_date',
    'user_id'
  ];

  protected $casts = [
    'issued_date' => 'datetime'
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

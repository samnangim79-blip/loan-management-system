<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Passbook extends Model
{
  protected $table = 'passbooks';
  protected $primaryKey = 'passbook_id';
  public $timestamps = false;

  protected $fillable = [
    'acct_id',
    'passbook_no',
    'last_printed_page',
    'last_printed_line',
    'status'
  ];

  public function account()
  {
    return $this->belongsTo(AccountInfo::class, 'acct_id', 'acct_id');
  }
}

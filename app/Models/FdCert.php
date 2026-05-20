<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FdCert extends Model
{
    protected $table = 'fd_certs';
    protected $primaryKey = 'fd_cert_id';
    public $timestamps = false;
    public $incrementing = false;

    protected $fillable = [
        'fd_cert_id',
        'acct_id',
        'date_issue',
        'matured_date',
        'amount',
        'int_rate',
        'extra_rate',
        'fd_option_id',
        'fd_term_id',
        'acct_for_int',
        'acct_for_prin',
        'future_dep_date',
        'done_by'
    ];

    protected $casts = [
        'date_issue' => 'date',
        'matured_date' => 'date',
        'future_dep_date' => 'date',
        'amount' => 'decimal:5',
        'int_rate' => 'decimal:2',
        'extra_rate' => 'decimal:2'
    ];

    public function account()
    {
        return $this->belongsTo(AccountInfo::class, 'acct_id', 'acct_id');
    }

    public function fdOption()
    {
        return $this->belongsTo(FdOption::class, 'fd_option_id', 'fd_option_id');
    }

    public function fdTerm()
    {
        return $this->belongsTo(FdTerm::class, 'fd_term_id', 'fd_term_id');
    }

    public function futureDeps()
    {
        return $this->hasMany(FdFutureDep::class, 'fd_cert_id', 'fd_cert_id');
    }

    public function rollOvers()
    {
        return $this->hasMany(FdRollOver::class, 'fd_cert_id', 'fd_cert_id');
    }

    public function transactions()
    {
        return $this->hasMany(FdTran::class, 'fd_cert_id', 'fd_cert_id');
    }
}

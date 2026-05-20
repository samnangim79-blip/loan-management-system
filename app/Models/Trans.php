<?php
// app/Models/Trans.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trans extends Model
{
    protected $table = 'trans';
    protected $primaryKey = 'tran_id';
    public $timestamps = false;

    protected $fillable = [
        'branch_id', 'tran_date', 'gl_map_id', 'amount', 'ccy_id',
        'discription', 'user_id', 'done_date', 'approved_by', 'tran_type'
    ];

    protected $casts = [
        'tran_date' => 'date',
        'done_date' => 'datetime',
        'amount' => 'decimal:5',
        'tran_type' => 'integer'
    ];

    const TYPE_DEPOSIT = 1;
    const TYPE_WITHDRAWAL = 2;
    const TYPE_TRANSFER = 3;
    const TYPE_LOAN_DISBURSEMENT = 4;
    const TYPE_LOAN_PAYMENT = 5;
    const TYPE_INTEREST = 6;
    const TYPE_FEE = 7;

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'branch_id');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'ccy_id', 'ccy_id');
    }

    public function glMap()
    {
        return $this->belongsTo(GlMap::class, 'gl_map_id', 'gl_map_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by', 'id');
    }

    public function tranDetails()
    {
        return $this->hasMany(TranDetail::class, 'tran_id', 'tran_id');
    }

    public function customerTransactions()
    {
        return $this->hasMany(CustAcctTran::class, 'tran_id', 'tran_id');
    }

    public function loanTransactions()
    {
        return $this->hasMany(LoanTran::class, 'tran_id', 'tran_id');
    }

    public function isApproved()
    {
        return !is_null($this->approved_by);
    }
}

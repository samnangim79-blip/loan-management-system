<?php
// app/Models/CustAcctTran.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustAcctTran extends Model
{
    protected $table = 'cust_acct_trans';
    protected $primaryKey = 'cust_tran_id';
    public $timestamps = false;

    protected $fillable = [
        'tran_id', 'acc_id', 'amt', 'dr_cr', 'os_bal', 'passbook_flag'
    ];

    protected $casts = [
        'amt' => 'decimal:5',
        'os_bal' => 'decimal:5',
        'passbook_flag' => 'integer'
    ];

    public function transaction()
    {
        return $this->belongsTo(Trans::class, 'tran_id', 'tran_id');
    }

    public function account()
    {
        return $this->belongsTo(AccountInfo::class, 'acc_id', 'acct_id');
    }

    public function isDebit()
    {
        return $this->DR_CR === 'd';
    }

    public function isCredit()
    {
        return $this->DR_CR === 'c';
    }
}

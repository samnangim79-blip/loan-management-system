<?php
// app/Models/AccountInfo.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccountInfo extends Model
{
    protected $table = 'account_infos';
    protected $primaryKey = 'acct_id';
    public $timestamps = false;

    protected $fillable = [
        'cust_id', 'acct_name', 'acct_no', 'acct_type_id', 'joint_flag',
        'mandatory', 'account_status', 'branch_id', 'opened_date',
        'opened_by', 'last_withdraw_date', 'close_date', 'close_by',
        'category', 'resident', 'currency_id', 'extra_rate', 'remark',
        'modify_by', 'modify_date'
    ];

    protected $casts = [
        'opened_date' => 'date',
        'last_withdraw_date' => 'date',
        'close_date' => 'date',
        'account_status' => 'integer',
        'joint_flag' => 'integer'
    ];

    const STATUS_ACTIVE = 1;
    const STATUS_DORMANT = 2;
    const STATUS_SUSPENDED = 3;
    const STATUS_CLOSED = 4;

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'cust_id', 'cust_id');
    }

    public function accountType()
    {
        return $this->belongsTo(AccountType::class, 'acct_type_id', 'acct_type_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'branch_id');
    }

    public function loans()
    {
        return $this->hasMany(LoanSchedule::class, 'acct_id', 'acct_id');
    }

    public function transactions()
    {
        return $this->hasMany(CustAcctTran::class, 'acc_id', 'acct_id');
    }

    public function jointHolders()
    {
        return $this->hasMany(JointAccountHolder::class, 'acct_id', 'acct_id');
    }

    public function photos()
    {
        return $this->hasMany(AccountPhoto::class, 'acct_id', 'acct_id');
    }

    public function getBalance()
    {
        $lastTransaction = $this->transactions()
            ->orderBy('cust_tran_id', 'desc')
            ->first();

        return $lastTransaction ? $lastTransaction->os_bal : 0;
    }

    public function getStatusTextAttribute()
    {
        $statuses = [
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_DORMANT => 'Dormant',
            self::STATUS_SUSPENDED => 'Suspended',
            self::STATUS_CLOSED => 'Closed'
        ];

        return $statuses[$this->account_status] ?? 'Unknown';
    }
}

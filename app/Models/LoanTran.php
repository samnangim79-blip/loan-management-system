<?php
// app/Models/LoanTran.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanTran extends Model
{
    protected $table = 'loan_trans';
    protected $primaryKey = 'loan_tran_id';
    public $timestamps = false;

    protected $fillable = [
        'tran_id', 'loan_schedule_id', 'loan_tran_type_id',
        'due_date', 'amount', 'os_balance'
    ];

    protected $casts = [
        'due_date' => 'date',
        'amount' => 'decimal:5',
        'os_balance' => 'decimal:5'
    ];

    const TYPE_DISBURSEMENT = 1;
    const TYPE_PAYMENT = 2;
    const TYPE_INTEREST = 3;
    const TYPE_PENALTY = 4;
    const TYPE_WRITE_OFF = 5;

    public function loanSchedule()
    {
        return $this->belongsTo(LoanSchedule::class, 'loan_schedule_id', 'loan_schedule_id');
    }

    public function transaction()
    {
        return $this->belongsTo(Trans::class, 'tran_id', 'tran_id');
    }

    public function loanTranType()
    {
        return $this->belongsTo(LoanTranType::class, 'loan_tran_type_id', 'loan_tran_type_id');
    }
}

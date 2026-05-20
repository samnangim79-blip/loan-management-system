<?php
// app/Models/PaymentFrequency.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentFrequency extends Model
{
    protected $table = 'payment_frequencys';
    protected $primaryKey = 'frequency_id';
    public $timestamps = false;

    protected $fillable = [
        'frequency', 'num_days'
    ];

    protected $casts = [
        'num_days' => 'integer'
    ];

    public function loanSchedules()
    {
        return $this->hasMany(LoanSchedule::class, 'frequency_id', 'frequency_id');
    }

    public function getPeriodsPerYear()
    {
        if ($this->num_days == 0) return 0;
        return floor(365 / $this->num_days);
    }
}

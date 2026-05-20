<?php
// app/Models/Currency.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    protected $table = 'currencys';
    protected $primaryKey = 'ccy_id';
    public $timestamps = false;

    protected $fillable = [
        'currency', 'ccy_rate', 'round_value', 'decimal_place',
        'compare_value', 'value_format'
    ];

    protected $casts = [
        'ccy_rate' => 'decimal:5',
        'round_value' => 'integer',
        'decimal_place' => 'integer',
        'compare_value' => 'integer',
        'value_format' => 'integer'
    ];

    public function accountTypes()
    {
        return $this->hasMany(AccountType::class, 'ccy_id', 'ccy_id');
    }

    public function convertToUSD($amount)
    {
        if ($this->ccy_rate == 0) return 0;
        return $amount / $this->ccy_rate;
    }

    public function convertFromUSD($amount)
    {
        return $amount * $this->ccy_rate;
    }

    public function formatAmount($amount)
    {
        return number_format($amount, $this->decimal_place);
    }
}

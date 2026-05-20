<?php

// app/Models/AccountType.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccountType extends Model
{
    protected $table = 'account_types';

    protected $primaryKey = 'acct_type_id';

    public $timestamps = false;

    protected $fillable = [
        'acct_type', 'ccy_id', 'resident', 'withhold_tax',
        'gl_id', 'withhold_gl', 'accrued_int_gl', 'interest_gl', 'category',
    ];

    protected $casts = [
        'withhold_tax' => 'decimal:2',
        'resident' => 'integer',
        'category' => 'integer',
    ];

    const CATEGORY_DEPOSIT = 0;

    const CATEGORY_TERM_DEPOSIT = 1;

    const CATEGORY_LOAN = 2;

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'ccy_id', 'ccy_id');
    }

    public function accounts()
    {
        return $this->hasMany(AccountInfo::class, 'acct_type_id', 'acct_type_id');
    }

    public function getCategoryTextAttribute()
    {
        $categories = [
            self::CATEGORY_DEPOSIT => 'Deposit',
            self::CATEGORY_TERM_DEPOSIT => 'Term Deposit',
            self::CATEGORY_LOAN => 'Loan',
        ];

        return $categories[$this->category] ?? 'Unknown';
    }
}

<?php
// app/Models/UserLogin.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class UserLogin extends Authenticatable
{
    protected $table = 'user_logins';
    protected $primaryKey = 'user_id';
    public $timestamps = false;

    protected $fillable = [
        'staff_id', 'login_name', 'password', 'next_pwd_expire',
        'failed_log', 'log_ip', 'status', 'sys_cash_limit',
        'profile_id', 'branch_id'
    ];

    protected $casts = [
        'next_pwd_expire' => 'date',
        'sys_cash_limit' => 'decimal:5',
        'status' => 'integer',
        'failed_log' => 'integer'
    ];

    protected $hidden = [
        'password'
    ];

    const STATUS_ACTIVE = 0;
    const STATUS_SUSPENDED = 1;
    const STATUS_DELETED = 2;

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id', 'staff_id');
    }

    public function profile()
    {
        return $this->belongsTo(AccessProfile::class, 'profile_id', 'profile_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'branch_id');
    }

    public function getAuthPassword()
    {
        return $this->password;
    }

    public function getAuthIdentifierName()
    {
        return 'user_id';
    }

    public function isActive()
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function hasPermission($permission)
    {
        if (!$this->profile) return false;

        return $this->profile->hasPermission($permission);
    }
}

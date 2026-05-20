<?php
// app/Models/AccessProfile.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccessProfile extends Model
{
    protected $table = 'access_profiles';
    protected $primaryKey = 'profile_id';
    public $timestamps = false;

    protected $fillable = [
        'profile', 'deposit_limit', 'withdrawal_limit',
        'loan_limit', 'non_cash_limit'
    ];

    protected $casts = [
        'deposit_limit' => 'decimal:5',
        'withdrawal_limit' => 'decimal:5',
        'loan_limit' => 'decimal:5',
        'non_cash_limit' => 'decimal:5'
    ];

    public function users()
    {
        return $this->hasMany(UserLogin::class, 'profile_id', 'profile_id');
    }

    public function profileDetails()
    {
        return $this->hasMany(AccessProfileDetail::class, 'profile_id', 'profile_id');
    }

    public function modules()
    {
        return $this->belongsToMany(Module::class, 'access_profile_details', 'profile_id', 'module_id');
    }

    public function hasPermission($moduleId)
    {
        return $this->profileDetails()->where('module_id', $moduleId)->exists();
    }

    public function hasModule($moduleControlName)
    {
        return $this->modules()->where('control_name', $moduleControlName)->exists();
    }
}

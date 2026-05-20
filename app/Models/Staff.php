<?php
// app/Models/Staff.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    protected $table = 'staffs';
    protected $primaryKey = 'staff_id';
    public $timestamps = false;

    protected $fillable = [
        'ic_no', 'full_name', 'gender', 'dob', 'pob',
        'address', 'phone', 'position', 'branch_id'
    ];

    protected $casts = [
        'dob' => 'date'
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'branch_id');
    }

    public function userLogin()
    {
        return $this->hasOne(UserLogin::class, 'staff_id', 'staff_id');
    }

    public function customers()
    {
        return $this->hasMany(Customer::class, 'staff_id', 'staff_id');
    }
}

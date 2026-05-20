<?php
// app/Models/Branch.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $table = 'branchs';
    protected $primaryKey = 'branch_id';
    public $timestamps = false;
    public $incrementing = false;

    protected $fillable = [
        'branch_id', 'branch_name', 'phone', 'email', 'website'
    ];

    public function staff()
    {
        return $this->hasMany(Staff::class, 'branch_id', 'branch_id');
    }

    public function accounts()
    {
        return $this->hasMany(AccountInfo::class, 'branch_id', 'branch_id');
    }

    public function users()
    {
        return $this->hasMany(UserLogin::class, 'branch_id', 'branch_id');
    }
}

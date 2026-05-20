<?php
// app/Models/Nationality.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nationality extends Model
{
    protected $table = 'nationalitys';
    protected $primaryKey = 'nationality_id';
    public $timestamps = false;

    protected $fillable = [
        'nationality', 'nationality_kh'
    ];

    public function customers()
    {
        return $this->hasMany(Customer::class, 'nationality_id', 'nationality_id');
    }
}

<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = ['first_name', 'last_name', 'email', 'position', 'department_id'];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}

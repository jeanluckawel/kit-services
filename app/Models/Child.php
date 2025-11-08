<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Child extends Model
{
    //

    protected $fillable = [
        'employee_id', 'first_name', 'last_name', 'full_name', 'children_status', 'gender', 'age', 'birthday', 'status',
    ];

    public function child()
    {
        return $this->hasMany(\App\Models\Child::class, 'employee_id', 'employee_id');
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $fillable = [
        'member_code',
        'name',
        'phone',
        'email',
        'address',
    ];

    public function loans()
    {
        return $this->hasMany(Loan::class);
    }
}
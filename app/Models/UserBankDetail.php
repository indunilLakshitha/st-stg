<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserBankDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'account_number',
        'holder_name',
        'branch',
        'bank_name',
        'user_id',
        'status',
    ];

    const ACTIVE = 1;
    const INACTIVE = 0;
}

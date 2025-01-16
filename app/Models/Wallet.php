<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'balance',
        'status',
    ];

    const STATUS = [
        'ACTIVE' => 1,
        'INACTIVE' => 2,
        'CANSELED' => 3
    ];

    const TYPE = [
        'ADDED' => 1,
        'REMOVED' => 2
    ];

    const COMISSION_TYPE = [
        'REFERRAL ' => 1,
        'DIRECT' => 2,
        'PURCHASE' => 3,
    ];
}

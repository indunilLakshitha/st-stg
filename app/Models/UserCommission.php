<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCommission extends Model
{
    use HasFactory;

    const STATUS = [
        'UNPAID' => 1,
        'PAID' => 2
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ComissionLevel extends Model
{
    use HasFactory, SoftDeletes;

    const STATUS = [
        'ACTIVE' => 1,
        'INACTIVE' =>  2
    ];

    const STATUS_LIST = [
        self::STATUS
    ];
}

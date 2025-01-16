<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PointAddingHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'run_by',
        'started_at',
    ];
}

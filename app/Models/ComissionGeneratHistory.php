<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ComissionGeneratHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'run_by',
        'started_at',
        'ended_at',
        'status',
    ];

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'run_by')->select('id', 'name');
    }
}

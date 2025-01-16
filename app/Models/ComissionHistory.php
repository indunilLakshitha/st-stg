<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class ComissionHistory extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'from_user_id',
        'left_points',
        'right_points',
        'type'
    ];

    const TYPE = [
        'ADDED' => 1,
        'REMOVED' => 2
    ];

    const COMISSION_TYPE = [
        'GSC' => 1,
        'DSC' => 2
    ];


    public function owner(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id')->select('id', 'name');
    }

    public function given(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'from_user_id')->select('id', 'name');
    }
}

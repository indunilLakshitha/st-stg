<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class UserPuchasedCourse extends Model
{
    use HasFactory;

    const TYPE = [
        'REFERRAL' => 1,
        'DIRECT' => 2,
        'CUSTOM' => 3
    ];

    const STATUS = [
        'APPLIED' => 1,
        'APPROVED' => 2,
        'CANSELED' => 3
    ];

    const PURCHASED_PERCENTS = [
        'HALF' => 1,
        'FULL' => 2,
    ];


    public function course(): HasOne
    {
        return $this->hasOne(Course::class, 'id', 'course_id')
            ->select('name', 'id', 'referer_commission', 'course_point', 'course_price', 'installment_1', 'installment_2');
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id')->select('name', 'id');
    }
}

<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use SoftDeletes;

    // user types
    const USER_TYPE = [
        'MAIN' => 'M',
        'LEFT' => 'A1',
        'RIGHT' => 'A2'
    ];

    // user types
    const USER_STATUS = [
        'NONE' => '1',
        'HALF' => '2',
        'FULL' => '3',
        'ER' => '4'
    ];

    // user ststus labels
    const USER_STATUS_LABLE = [
        '1' => 'NONE',
        '2' => 'HALF',
        '3' => 'FULL',
        '4' => 'ER'
    ];

    const USER_TYPE_LABLE = [
        'M' => 'MAIN',
        'A1' => 'DUMMY',
        'A2' => 'DUMMY',

    ];
    const GENDER_LIST = ['Male', 'Female','Other'];

    const MAIN = 'M';
    const LEFT = 'A1';
    const RIGHT = 'A2';

    // user status
    const NONE = 1;
    const FULL = 2;
    const HALF = 3;
    const ER = 4;

    // user active_status
    const UNBLOCKED = 1;
    const BLOCKED = 2;

    const PAYMENT_TYPE =  [
        'BANK TRANSFER' => 1,
        'ONLINE' => 2
    ];

    const PAYMENT_STATUS =  [
        'PENDING' => 1,
        'HALF' => 2,
        'FULL' => 3
    ];


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'first_name',
        'last_name',
        'referrer_approved_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        // 'profile_photo_url',
    ];

    // protected $with = ['childA1', 'childA2'];

    public function referrer(): HasOne
    {
        return $this->hasOne(User::class, 'reg_no', 'referrer_id')->select(
            'id',
            'reg_no',
            'er_status',
            'first_name',
            'last_name',
            'name',
            'dummy_a2_id',
            'dummy_a1_id',
            'parent_id',
            'left_points',
            'right_points'
        );
    }

    public function childA1(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'dummy_a1_id')
            ->select(
                'id',
                'reg_no',
                'er_status',
                'name',
                'first_name',
                'last_name',
                'dummy_a2_id',
                'dummy_a1_id',
                'parent_id',
                'left_points',
                'right_points'
            );
    }

    public function childA2(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'dummy_a2_id')
            ->select(
                'id',
                'reg_no',
                'er_status',
                'first_name',
                'last_name',
                'name',
                'dummy_a2_id',
                'dummy_a1_id',
                'parent_id',
                'left_points',
                'right_points'
            );
    }

    public function purchase(): HasOne
    {
        return $this->hasOne(UserPuchasedCourse::class, 'user_id', 'id')
            ->where('type', UserPuchasedCourse::TYPE['REFERRAL'])
            ->select('id', 'user_id', 'course_id', 'type');
    }
    public function bank(): HasOne
    {
        return $this->hasOne(UserBankDetail::class, 'user_id', 'id');
    }

    public function getChildAttribute()
    {
        return $this->childA1()->get()->toArray();
    }
}

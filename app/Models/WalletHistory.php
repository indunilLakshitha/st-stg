<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class WalletHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'wallet_id',
        'amount',
        'balance',
        'type',
        'requested_at',
        'requested_by',
        'status',
        'comission_type',
        'created_at',
        'updated_at',
    ];


    const TYPE = [
        'ADDED' => 1,
        'REMOVED' => 2
    ];

    const STATUS = [
        'REQUESTED' => 1,
        'TRANSFERED' => 2,
        'CANSELED' => 3
    ];

    const COMISSION_TYPES = [
        'DIRECT' => 2,
        'GSC' => 1,
        'DUMMY_TRANSFERED' => 3
    ];

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id')->select('id', 'first_name', 'last_name', 'reg_no', 'name', 'type');
    }

    public function admin(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'paid_by')->select('id', 'name');
    }

    public function wallet(): HasOne
    {
        return $this->hasOne(Wallet::class, 'id', 'wallet_id')->select('id', 'balance');
    }
}

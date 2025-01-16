<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApproveHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'approved_referrer_id',
        'assigned_id_by_referrer',
        'assigned_side_by_referrer',
        'referrer_approved_at',
        'approved_admin_id',
        'actual_assigned_id',
        'actual_assigned_side',
        'admin_approved_at'
    ];
}

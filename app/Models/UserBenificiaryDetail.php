<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserBenificiaryDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'relationship',
        'contact_no',
        'user_id',
    ];
}

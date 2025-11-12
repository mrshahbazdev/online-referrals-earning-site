<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WithdrawalRequest extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'amount',
        'wallet_address',
        'status',
    ];

    /**
     * Get the user that owns the withdrawal request.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

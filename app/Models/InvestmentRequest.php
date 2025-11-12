<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvestmentRequest extends Model
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
        'transaction_id_image_url',
        'status',
    ];

    /**
     * Get the user that owns the investment request.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

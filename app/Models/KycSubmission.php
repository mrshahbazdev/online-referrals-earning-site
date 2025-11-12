<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KycSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'full_name',
        'id_card_number',
        'id_card_front_url',
        'id_card_back_url',
        'face_image_url', // Yeh line add karein
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

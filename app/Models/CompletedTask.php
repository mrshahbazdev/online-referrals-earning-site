<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompletedTask extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'task_id',
        'completed_at',
    ];

    /**
     * The "booted" method of the model.
     * We are disabling the updated_at timestamp for this model.
     */
    protected static function booted(): void
    {
        static::creating(function ($model) {
            $model->completed_at = now();
        });
    }

    public $timestamps = false; // Disable default timestamps
}

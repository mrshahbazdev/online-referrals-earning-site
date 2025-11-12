<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'level_id', // Isay add karein
        'title',
        'task_type',
        'task_url',
        'reward_amount',
        'duration',
    ];
    public function level()
    {
        return $this->belongsTo(Level::class);
    }
    public function getYoutubeIdAttribute(): ?string
    {
        // Yeh function ab sirf tab kaam karega jab task type 'youtube_watch' ho
        if ($this->task_type !== 'youtube_watch') {
            return null;
        }

        $pattern = '%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i';
        if (preg_match($pattern, $this->task_url, $match)) {
            return $match[1];
        }
        return null;
    }
}

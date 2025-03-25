<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

    protected $table="feedbacks";
    protected $fillable = ['task_id', 'data'];


    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function photos()
    {
        return $this->hasMany(FeedbackPhoto::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Task extends Model
{
    use HasFactory;

    protected $guarded = [];


    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $userId = Auth::id();
            $model->created_by = $userId;
            $model->updated_by = $userId;
        });

        static::updating(function ($model) {
            $userId = Auth::id();
            $model->updated_by = $userId;
        });
    }

    protected $appends = ['feedback_submitted'];

    public function getFeedbackSubmittedAttribute()
    {
        return $this->feedback()->exists();
    }


    public function financial()
    {
        return $this->hasOne(Financial::class, 'task_id');
    }


    // Relationships
    public function feedback()
    {
        return $this->hasOne(Feedback::class);
    }
    public function creator()
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function jobOrderType()
    {
        return $this->belongsTo(JobOrderType::class);
    }


    public function type()
    {
        return $this->belongsTo(Type::class);
    }
}

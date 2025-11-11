<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use Symfony\Component\Console\Question\Question;

class Test extends Model
{
    //
    use HasFactory;
    protected $fillable = [
        'title',
        'description',
        'duration_minutes',
        'start_at',
        'ends_at',
        'mq_count',
        'essay_count',
        'shuffle_questions',
        'created_by'
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'shuffle_questions' => 'boolean',
    ];

    public function questions (){
        return $this->hasMany(Question::class);
    }
    public function creator(){
        return $this->belongsTo(User::class,'created_by');
    }

    public function assignees(){
        return $this->belongsToMany(User::class)->withPivot(['status','started_at','finished_at','total_score'])->withTimestamps();
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamSession extends Model
{
    protected $table = 'exam_sessions';

    protected $fillable = [
        'test_id',
        'user_id',
        'session_token',
        'started_at',
        'finished_at',
        'violations',
        'status',
    ];

    protected $dates = ['started_at', 'finished_at'];

    public function test()
    {
        return $this->belongsTo(\App\Models\Test::class, 'test_id');
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }
}

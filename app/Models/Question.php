<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = [
        'test_id', 'type', 'text', 'score', 'sort_order',
        'answer_key', 'choices', 'created_by',
    ];

    protected $casts = [
        'choices' => 'array',
    ];

    public function test()
    {
        return $this->belongsTo(\App\Models\Test::class);
    }
}

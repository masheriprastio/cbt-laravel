<?php

 namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = [
        'test_id','type','text','score','sort_order','choices','answer_key','created_by',
    ];

    protected $casts = [
        'choices' => 'array', // <— penting
    ];

    public function test()
    {
        return $this->belongsTo(Test::class);
    }
}


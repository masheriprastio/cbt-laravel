<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    //
    use HasFactory;
    protected $fillable = ['test_id','type','text','answer_key','score','order','created_by'];

    public function test(){
        return $this->belongsTo(Test::class);
    }
    public function options(){
        return $this->hasMany(Option::class);
    }
}

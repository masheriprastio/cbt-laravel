<?php
namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Answer extends Model
{
use HasFactory;
protected $fillable = [
'test_id','question_id','user_id','selected_option','answer_text','is_correct','score','graded_by','graded_at'
];


protected $casts = [ 'graded_at' => 'datetime' ];
}
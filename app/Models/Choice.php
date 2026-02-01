<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Choice extends Model
{
    protected $fillable = ['question_id', 'choice_label', 'choice_text', 'is_correct'];

    protected $casts = [
        'is_correct' => 'boolean',  // เพิ่มบรรทัดนี้
    ];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}

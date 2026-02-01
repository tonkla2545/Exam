<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = ['topic_id', 'question_text', 'explanation'];

    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }

    public function choices()
    {
        return $this->hasMany(Choice::class);
    }
}

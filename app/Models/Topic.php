<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    protected $fillable = ['agency_id', 'name', 'description'];

    public function agency()
    {
        return $this->belongsTo(Agency::class);
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }
}

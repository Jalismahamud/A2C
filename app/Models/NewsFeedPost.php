<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsFeedPost extends Model
{
    protected $guarded = [];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
}

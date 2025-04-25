<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    //fillable fields
    protected $fillable = [
        'conversation_id',
        'user_id',
        'message',
    ];

    //belongs to user 
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

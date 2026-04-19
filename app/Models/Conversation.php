<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    protected $fillable = ['workspace_id', 'title'];

    public function workspace()
    {
        return $this->belongsTo(Workspace::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}

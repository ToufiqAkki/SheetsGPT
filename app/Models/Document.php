<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $fillable = ['workspace_id', 'file_path', 'original_name', 'mime_type', 'content'];

    public function workspace()
    {
        return $this->belongsTo(Workspace::class);
    }
}

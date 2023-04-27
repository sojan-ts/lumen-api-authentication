<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['title', 'visibility', 'type'];

    public function type()
    {
        return $this->belongsTo(Type::class);
    }
}

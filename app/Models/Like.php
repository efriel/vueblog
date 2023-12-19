<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Interfaces\Likeable;

class Like extends Model
{
    use HasFactory;
    protected $table = 'likes';
    protected $fillable = ['is_liked'];
    
    public function likeable()
    {
        return $this->morphTo();
    }

    public function userable()
    {
        return $this->morphTo();
    }
}
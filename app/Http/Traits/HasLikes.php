<?php
namespace App\Http\Traits;

use App\Interfaces\Likeable;

trait HasLikes
{
    public function likes(): MorphMany
    {
        return $this->morphMany(Like::class, 'likeable')->where('is_like', true);
    }
    public function dislikes(): MorphMany
    {
        return $this->morphMany(Like::class, 'likeable')->where('is_like', false);
    } 
}
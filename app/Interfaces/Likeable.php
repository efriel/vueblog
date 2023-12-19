<?php

namespace App\Interfaces;
use App\Http\Traits\HasLikes;
use App\Http\Traits\HasLikeable;

interface Likeable
{
    public function likes(): MorphMany;
    public function dislikes(): MorphMany; 
}
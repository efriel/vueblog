<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Interfaces\Likeable;
use App\Http\Traits\HasLikes;

class Posts extends Model
{
    use HasFactory, HasLikes;

    protected $table = 'posts';
    protected $dates = ['deleted_at'];

    protected $fillable = array('title','slug','content','author_id','post_date');
    public $timestamps = true;

    public function Author(){
        return $this->belongsTo('App\User','author_id');
    }

    public function Postuser()
    {
        return $this->hasMany('App\User', "id", "author_id");
    }

    public function comments()
    {
        return $this->hasMany(Comments::class)->whereNull('parent_id');
    }
}

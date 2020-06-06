<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
    	'user_id', 'body', 'post_id'
    ];

    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id')->withTrashed();
    }

    public function user(){

    	return $this->belongsTo(User::class, 'user_id')->withTrashed();

    }
}

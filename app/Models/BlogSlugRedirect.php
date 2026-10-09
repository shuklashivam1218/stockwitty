<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlogSlugRedirect extends Model
{
    protected $fillable = ['old_slug', 'blog_post_id'];

    public function post()
    {
        return $this->belongsTo(BlogPost::class, 'blog_post_id');
    }
}

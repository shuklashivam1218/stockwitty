<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UnlistedAbout extends Model
{
    protected $table      = 'unlisted_about';
    protected $primaryKey = 'UL_ABOUT_ID';
    public    $timestamps = false;

    protected $fillable = [
        'UL_ABOUT_FINCODE',
        'UL_ABOUT_CONTENT',
        'UL_ABOUT_ACTIVE',
        'UL_ABOUT_INSERT_TIME',
        'UL_ABOUT_UPDATE_TIME',
    ];
}

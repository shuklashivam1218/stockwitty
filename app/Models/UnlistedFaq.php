<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UnlistedFaq extends Model
{
    protected $table      = 'unlisted_faqs';
    protected $primaryKey = 'UL_FAQ_ID';
    public    $timestamps = false;

    protected $fillable = [
        'UL_FAQ_FINCODE',
        'UL_FAQ_TARGET',
        'UL_FAQ_TAB',
        'UL_FAQ_QUESTION',
        'UL_FAQ_ANSWER',
        'UL_FAQ_SORT_ORDER',
        'UL_FAQ_ACTIVE',
        'UL_FAQ_INSERT_TIME',
        'UL_FAQ_UPDATE_TIME',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UnlistedSeoMeta extends Model
{
    protected $table      = 'unlisted_seo_meta';
    protected $primaryKey = 'UL_SEO_ID';
    public    $timestamps = false;

    protected $fillable = [
        'UL_SEO_FINCODE',
        'UL_SEO_COMPANY_TITLE', 'UL_SEO_COMPANY_DESCRIPTION', 'UL_SEO_COMPANY_KEYWORDS',
        'UL_SEO_ABOUT_TITLE', 'UL_SEO_ABOUT_DESCRIPTION', 'UL_SEO_ABOUT_KEYWORDS',
        'UL_SEO_THESIS_TITLE', 'UL_SEO_THESIS_DESCRIPTION', 'UL_SEO_THESIS_KEYWORDS',
        'UL_SEO_ACTIVE',
        'UL_SEO_INSERT_TIME',
        'UL_SEO_UPDATE_TIME',
    ];
}

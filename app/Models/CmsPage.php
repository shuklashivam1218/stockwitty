<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CmsPage extends Model
{
    protected $table      = 'cms_dynamic_pages';
    protected $primaryKey = 'CMS_PAGE_ID';
    public    $timestamps = false;

    protected $fillable = [
        'CMS_PAGE_SLUG',
        'CMS_PAGE_TITLE',
        'CMS_PAGE_DESCRIPTION',
        'CMS_PAGE_CONTENT',
        'CMS_PAGE_ACTIVE',
        'CMS_PAGE_INSERT_TIME',
        'CMS_PAGE_UPDATE_TIME',
    ];
}

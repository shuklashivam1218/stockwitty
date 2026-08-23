<?php

namespace App\Helpers;

use Illuminate\Http\UploadedFile;

/**
 * The saved filename's extension must always come from the file's real,
 * content-detected MIME type — never from the client-supplied filename
 * (UploadedFile::getClientOriginalExtension()) — or a polyglot file (valid
 * image bytes with an appended PHP payload) could be saved as executable
 * code inside the public web root. Every file upload that lands in the
 * public web root should resolve its extension through this class instead
 * of trusting the client, and its destination through webRoot() below.
 */
class SafeUpload
{
    private const IMAGE_MIME_EXTENSIONS = [
        'image/png'  => 'png',
        'image/jpeg' => 'jpg',
        'image/gif'  => 'gif',
        'image/webp' => 'webp',
        'image/svg+xml' => 'svg',
    ];

    public static function imageExtension(UploadedFile $file): ?string
    {
        return self::IMAGE_MIME_EXTENSIONS[$file->getMimeType()] ?? null;
    }

    /**
     * The real, web-served document root for public uploads (company logos,
     * thesis/about images, CMS page images, ...).
     *
     * On this app's Hostinger deployment, public_html/ sits next to the app
     * directory as a separate folder — it is NOT the same as public_path()
     * (<app>/public), which is not web-accessible there. deploy.sh only
     * rsyncs public_path('...') into public_html/ during deploy, so anything
     * written to public_path() directly is invisible to visitors until the
     * next deploy runs. Always write public uploads here instead, so they're
     * served immediately.
     */
    public static function webRoot(): string
    {
        $parentPublicHtml = dirname(base_path()) . DIRECTORY_SEPARATOR . 'public_html';

        return is_dir($parentPublicHtml) ? $parentPublicHtml : public_path();
    }
}

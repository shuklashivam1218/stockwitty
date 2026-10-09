<?php

namespace App\Helpers;

use App\Exceptions\ImageUploadException;
use Illuminate\Http\UploadedFile;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

/**
 * The one way to save, replace or delete a public image (company logos,
 * thesis/about/CMS images, blog images). Every rule here fixes a bug that
 * has already happened once in this app or in unlisted-stocks:
 *
 *  - Files go to SafeUpload::webRoot(), never public_path(): on Hostinger
 *    public_path() is not web-served, so files written there 404 until the
 *    next deploy.
 *  - The extension comes from the detected MIME type (SafeUpload), never the
 *    client filename, so a polyglot can't land in the web root as .php.
 *  - A failed mkdir/move becomes a readable ImageUploadException instead of
 *    a 500 (and the server path is logged, not shown to the admin).
 *  - delete() clears both webRoot() and the legacy public_path() copy, and
 *    only ever touches files under images/.
 *  - Folder, prefix and basename are sanitised, so a route parameter used as
 *    a filename prefix can't steer the file outside its folder.
 *
 * Paths are stored relative to the web root ("images/blog/x.webp") so they
 * survive a domain change; url() turns one into a full URL.
 *
 * Uploads live only on the server's public_html, so deploy.sh must never
 * --delete inside images/ (see the protect filter there).
 */
class ImageUpload
{
    /** Validation rule for any image field saved through this helper. */
    public const RULES = 'image|mimes:jpg,jpeg,png,gif,webp|max:5120';

    private const KNOWN_EXTENSIONS = ['png', 'jpg', 'jpeg', 'gif', 'webp', 'svg'];

    /** Saves under a new unique name: "{prefix}_{time}_{uniqid}.{ext}". Returns the relative path. */
    public static function store(UploadedFile $file, string $folder, string $prefix): string
    {
        $ext    = self::extension($file, false);
        $prefix = self::cleanName($prefix) ?: 'img';

        return self::move($file, $folder, $prefix . '_' . time() . '_' . uniqid() . '.' . $ext);
    }

    /**
     * Saves under a fixed name (e.g. a company logo named after its slug) and
     * removes any earlier copy with a different extension. The new file is
     * written first, so a failed upload never leaves the record without one.
     */
    public static function replace(UploadedFile $file, string $folder, string $basename, bool $allowSvg = false): string
    {
        $ext      = self::extension($file, $allowSvg);
        $basename = self::cleanName($basename);
        if ($basename === '') {
            throw new ImageUploadException('Invalid file name for the upload.');
        }

        $path = self::move($file, $folder, $basename . '.' . $ext);

        foreach (self::KNOWN_EXTENSIONS as $old) {
            if ($old !== $ext) {
                self::delete('images/' . self::cleanFolder($folder) . '/' . $basename . '.' . $old);
            }
        }

        return $path;
    }

    /** Deletes a file saved by this helper. Anything outside images/ is ignored. */
    public static function delete(?string $relativePath): void
    {
        if (!self::isManagedPath($relativePath)) {
            return;
        }

        $relative = str_replace('/', DIRECTORY_SEPARATOR, $relativePath);
        foreach (array_unique([SafeUpload::webRoot(), public_path()]) as $root) {
            $full = $root . DIRECTORY_SEPARATOR . $relative;
            if (is_file($full)) {
                @unlink($full);
            }
        }
    }

    public static function url(?string $relativePath): ?string
    {
        return $relativePath ? asset($relativePath) : null;
    }

    private static function extension(UploadedFile $file, bool $allowSvg): string
    {
        if (!$file->isValid()) {
            throw new ImageUploadException('Uploaded file is invalid.');
        }

        $ext = SafeUpload::imageExtension($file);
        if ($ext === null || ($ext === 'svg' && !$allowSvg)) {
            throw new ImageUploadException('Uploaded file is not a recognised image type.');
        }

        return $ext;
    }

    private static function move(UploadedFile $file, string $folder, string $filename): string
    {
        $folder = self::cleanFolder($folder);
        $dir    = SafeUpload::webRoot() . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR
                . str_replace('/', DIRECTORY_SEPARATOR, $folder);

        if (!is_dir($dir) && !@mkdir($dir, 0755, true) && !is_dir($dir)) {
            report(new \RuntimeException('Could not create upload directory: ' . $dir));
            throw new ImageUploadException('Could not create the upload folder. Please contact the developer.');
        }

        try {
            $file->move($dir, $filename);
        } catch (FileException $e) {
            report($e);
            throw new ImageUploadException('Image upload failed. Please try again.');
        }

        return 'images/' . $folder . '/' . $filename;
    }

    /** "blog/featured" is fine; "../x", "/abs" or "a//b" is a bug in the caller. */
    private static function cleanFolder(string $folder): string
    {
        if (!preg_match('#^[A-Za-z0-9_-]+(?:/[A-Za-z0-9_-]+)*$#', $folder)) {
            throw new \InvalidArgumentException("Invalid upload folder: {$folder}");
        }

        return $folder;
    }

    private static function cleanName(string $name): string
    {
        return preg_replace('/[^A-Za-z0-9_-]/', '', $name);
    }

    private static function isManagedPath(?string $path): bool
    {
        return is_string($path)
            && preg_match('#^images/[A-Za-z0-9_-]+(?:/[A-Za-z0-9_-]+)*/[A-Za-z0-9_-]+\.[A-Za-z0-9]+$#', $path) === 1;
    }
}

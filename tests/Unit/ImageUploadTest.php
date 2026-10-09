<?php

namespace Tests\Unit;

use App\Exceptions\ImageUploadException;
use App\Helpers\ImageUpload;
use App\Helpers\SafeUpload;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class ImageUploadTest extends TestCase
{
    private const FOLDER = 'phpunit-image-upload';

    protected function tearDown(): void
    {
        $dir = SafeUpload::webRoot() . '/images/' . self::FOLDER;
        if (is_dir($dir)) {
            array_map('unlink', glob($dir . '/*'));
            rmdir($dir);
        }

        parent::tearDown();
    }

    private function fullPath(string $relative): string
    {
        return SafeUpload::webRoot() . '/' . $relative;
    }

    public function test_store_saves_into_web_root_and_returns_relative_path(): void
    {
        $path = ImageUpload::store(UploadedFile::fake()->image('photo.png'), self::FOLDER, 'blog_1');

        $this->assertMatchesRegularExpression('#^images/' . self::FOLDER . '/blog_1_\d+_[a-f0-9]+\.png$#', $path);
        $this->assertFileExists($this->fullPath($path));
        $this->assertSame(asset($path), ImageUpload::url($path));
    }

    public function test_extension_comes_from_content_not_client_name(): void
    {
        $file = UploadedFile::fake()->image('evil.php.jpg');
        $path = ImageUpload::store($file, self::FOLDER, 'x');

        $this->assertStringEndsWith('.jpg', $path);
        $this->assertStringNotContainsString('php', basename($path, '.jpg'));
    }

    public function test_php_named_as_png_is_rejected(): void
    {
        // A real UploadedFile, not UploadedFile::fake(): fakes report a MIME
        // type from the filename, real uploads sniff the content.
        $tmp = tempnam(sys_get_temp_dir(), 'upl');
        file_put_contents($tmp, '<?php echo 1;');
        $file = new UploadedFile($tmp, 'shell.png', null, null, true);

        $this->expectException(ImageUploadException::class);

        ImageUpload::store($file, self::FOLDER, 'x');
    }

    public function test_svg_is_rejected_unless_allowed(): void
    {
        $svg = '<svg xmlns="http://www.w3.org/2000/svg"><script>alert(1)</script></svg>';

        $this->expectException(ImageUploadException::class);

        ImageUpload::store(UploadedFile::fake()->createWithContent('a.svg', $svg), self::FOLDER, 'x');
    }

    public function test_prefix_cannot_escape_the_folder(): void
    {
        $path = ImageUpload::store(UploadedFile::fake()->image('a.png'), self::FOLDER, '../../evil');

        $this->assertStringStartsWith('images/' . self::FOLDER . '/evil_', $path);
    }

    public function test_bad_folder_is_a_programming_error(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        ImageUpload::store(UploadedFile::fake()->image('a.png'), '../outside', 'x');
    }

    public function test_replace_keeps_one_file_per_basename(): void
    {
        $png = ImageUpload::replace(UploadedFile::fake()->image('logo.png'), self::FOLDER, 'acme');
        $jpg = ImageUpload::replace(UploadedFile::fake()->image('logo.jpg'), self::FOLDER, 'acme');

        $this->assertSame('images/' . self::FOLDER . '/acme.jpg', $jpg);
        $this->assertFileExists($this->fullPath($jpg));
        $this->assertFileDoesNotExist($this->fullPath($png));
    }

    public function test_delete_removes_file_and_ignores_paths_outside_images(): void
    {
        $path = ImageUpload::store(UploadedFile::fake()->image('a.png'), self::FOLDER, 'x');
        ImageUpload::delete($path);
        $this->assertFileDoesNotExist($this->fullPath($path));

        // Must be a silent no-op, never an unlink outside images/.
        ImageUpload::delete('images/../index.php');
        ImageUpload::delete('.env');
        ImageUpload::delete(null);
        $this->assertFileExists(public_path('index.php'));

        // The site's own committed artwork (e.g. a migrated post's hero) is off-limits.
        ImageUpload::delete('images/sw/blog-how-to-buy-unlisted-shares.jpg');
        $this->assertFileExists(public_path('images/sw/blog-how-to-buy-unlisted-shares.jpg'));
    }

    public function test_exception_renders_json_for_ajax_callers(): void
    {
        $request = request();
        $request->headers->set('Accept', 'application/json');

        $response = (new ImageUploadException('Nope'))->render($request);

        $this->assertSame(422, $response->getStatusCode());
        $this->assertSame(['success' => false, 'message' => 'Nope'], $response->getData(true));
    }
}

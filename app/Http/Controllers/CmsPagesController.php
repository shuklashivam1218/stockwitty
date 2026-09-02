<?php

namespace App\Http\Controllers;

use App\Helpers\SafeUpload;
use App\Models\CmsPage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CmsPagesController extends Controller
{
    public function index()
    {
        $pages = CmsPage::orderBy('CMS_PAGE_TITLE')->get();

        return view('admin.cms.index', compact('pages'));
    }

    public function getEditModal(string $slug)
    {
        $page = CmsPage::where('CMS_PAGE_SLUG', $slug)->firstOrFail();

        return view('admin.cms.edit-modal', compact('page'));
    }

    public function update(Request $request, string $slug)
    {
        $page = CmsPage::where('CMS_PAGE_SLUG', $slug)->firstOrFail();

        $page->update([
            'CMS_PAGE_TITLE'       => $request->input('CMS_PAGE_TITLE'),
            'CMS_PAGE_DESCRIPTION' => $request->input('CMS_PAGE_DESCRIPTION'),
            'CMS_PAGE_CONTENT'     => $request->input('CMS_PAGE_CONTENT'),
            'CMS_PAGE_ACTIVE'      => $request->input('CMS_PAGE_ACTIVE', '1'),
            'CMS_PAGE_UPDATE_TIME' => now(),
        ]);

        return response()->json(['success' => true, 'message' => 'Page saved successfully.']);
    }

    public function uploadImage(Request $request, string $slug)
    {
        $request->validate(['file' => 'required|image|mimes:jpg,jpeg,png,gif,webp|max:5120']);

        $file = $request->file('file');
        $ext  = SafeUpload::imageExtension($file);
        if ($ext === null) {
            return response()->json(['message' => 'Uploaded file is not a recognised image type.'], 422);
        }

        $folder = SafeUpload::webRoot() . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'cms-pages-images';
        if (!is_dir($folder)) mkdir($folder, 0755, true);

        $filename = $slug . '_' . time() . '_' . uniqid() . '.' . $ext;
        $file->move($folder, $filename);

        return response()->json(['location' => asset('images/cms-pages-images/' . $filename)]);
    }

    public function showDisclaimer()
    {
        $page = CmsPage::where('CMS_PAGE_SLUG', 'disclaimer')
            ->where('CMS_PAGE_ACTIVE', '1')
            ->firstOrFail();

        ['toc' => $toc, 'html' => $content] = $this->extractToc($page->CMS_PAGE_CONTENT ?? '');

        return view('sw.disclaimer.index', compact('page', 'toc', 'content'));
    }

    /**
     * CMS content is raw admin-authored HTML (no fixed set of sections), so
     * unlike the rest of the site's hand-placed <x-sw.article-h2 id="...">
     * TOC anchors, the sidebar nav here has to be built from whatever <h2>
     * tags the admin actually wrote — id + scroll-anchor classes get
     * injected into the content itself so x-sw.toc-layout's scroll-spy can
     * find them.
     */
    private function extractToc(string $html): array
    {
        if (trim($html) === '') {
            return ['toc' => [], 'html' => $html];
        }

        $dom = new \DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML(
            '<?xml encoding="utf-8" ?><div id="__toc_root">' . $html . '</div>',
            LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
        );
        libxml_clear_errors();

        $toc  = [];
        $seen = [];

        foreach (iterator_to_array($dom->getElementsByTagName('h2')) as $h2) {
            $label = trim($h2->textContent);
            if ($label === '') {
                continue;
            }

            $base = Str::slug($label) ?: 'section';
            $id   = $base;
            $n    = 2;
            while (in_array($id, $seen, true)) {
                $id = $base . '-' . $n++;
            }
            $seen[] = $id;

            $h2->setAttribute('id', $id);
            $existingClass = $h2->getAttribute('class');
            $h2->setAttribute('class', trim($existingClass . ' mt-14 scroll-mt-28 text-2xl font-bold text-foreground sm:text-3xl'));

            $toc[] = ['id' => $id, 'label' => $label];
        }

        $root = $dom->getElementById('__toc_root');
        $newHtml = '';
        foreach ($root->childNodes as $child) {
            $newHtml .= $dom->saveHTML($child);
        }

        return ['toc' => $toc, 'html' => $newHtml];
    }
}

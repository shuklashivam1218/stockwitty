<?php

namespace App\Http\Controllers;

use App\Helpers\ImageUpload;
use App\Models\CmsPage;
use App\Support\HtmlToc;
use Illuminate\Http\Request;

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
        $request->validate(['file' => 'required|' . ImageUpload::RULES]);

        $path = ImageUpload::store($request->file('file'), 'cms-pages-images', $slug);

        return response()->json(['location' => ImageUpload::url($path)]);
    }

    public function showDisclaimer()
    {
        $page = CmsPage::where('CMS_PAGE_SLUG', 'disclaimer')
            ->where('CMS_PAGE_ACTIVE', '1')
            ->firstOrFail();

        ['toc' => $toc, 'html' => $content] = HtmlToc::build($page->CMS_PAGE_CONTENT ?? '');

        return view('sw.disclaimer.index', compact('page', 'toc', 'content'));
    }
}

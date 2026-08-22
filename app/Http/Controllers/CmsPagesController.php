<?php

namespace App\Http\Controllers;

use App\Models\CmsPage;
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
        $request->validate(['file' => 'required|image|mimes:jpg,jpeg,png,gif,webp|max:5120']);

        $folder = public_path('images/cms-pages-images');
        if (!is_dir($folder)) mkdir($folder, 0755, true);

        $file     = $request->file('file');
        $filename = $slug . '_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $file->move($folder, $filename);

        return response()->json(['location' => asset('images/cms-pages-images/' . $filename)]);
    }

    public function showDisclaimer()
    {
        $page = CmsPage::where('CMS_PAGE_SLUG', 'disclaimer')
            ->where('CMS_PAGE_ACTIVE', '1')
            ->firstOrFail();

        return view('public.disclaimer', compact('page'));
    }
}

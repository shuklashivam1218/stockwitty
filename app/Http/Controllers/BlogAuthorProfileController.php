<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

/** The signed-in author's own byline and author card (bio + social links). */
class BlogAuthorProfileController extends Controller
{
    public function edit()
    {
        $author = User::findOrFail(session('uid'));

        return view('admin.blog.author-profile', compact('author'));
    }

    public function update(Request $request)
    {
        $author = User::findOrFail(session('uid'));

        $data = $request->validate([
            'author_designation' => 'nullable|string|max:120',
            'author_bio'         => 'nullable|string|max:1000',
            'author_linkedin'    => 'nullable|url:https|max:255',
            'author_twitter'     => 'nullable|url:https|max:255',
            'author_facebook'    => 'nullable|url:https|max:255',
            'author_instagram'   => 'nullable|url:https|max:255',
            'author_website'     => 'nullable|url:http,https|max:255',
        ]);

        foreach (['author_designation', 'author_bio'] as $field) {
            if (isset($data[$field])) {
                $data[$field] = trim(strip_tags($data[$field]));
            }
        }

        $author->update($data);

        return redirect()->route('admin.blog.profile.edit')->with('success', 'Author profile updated.');
    }
}

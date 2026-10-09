<?php

namespace App\Http\Controllers;

use App\Models\Blog;

class BlogController extends Controller
{
    public function index()
    {
        $blogs = Blog::query()
            ->orderByDesc('date')
            ->orderByDesc('id')
            ->paginate(9);

        return view('pages.blog', compact('blogs'));
    }

    public function show($slug)
    {
        $blogQuery = Blog::query()
            ->where('url_name', $slug);
        if (is_numeric($slug)) {
            $blogQuery->orWhere('id', $slug);
        }
        $blog = $blogQuery->first();
        abort_if(! $blog, 404);
        $recentBlogs = Blog::query()
            ->where('id', '!=', $blog->id)
            ->orderByDesc('date')
            ->orderByDesc('id')
            ->limit(4)
            ->get();

        return view('pages.blog-details', compact('blog', 'recentBlogs'));
    }
}

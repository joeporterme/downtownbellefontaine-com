<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;

class BlogController extends Controller
{
    public function index()
    {
        // Feature the latest post on page 1; the grid always excludes it so
        // pagination stays consistent across pages.
        $featured = BlogPost::published()->latest('published_at')->first();

        $posts = BlogPost::published()
            ->with(['author', 'category'])
            ->when($featured, fn ($q) => $q->whereKeyNot($featured->getKey()))
            ->latest('published_at')
            ->paginate(9);

        $showFeatured = $featured && ! request()->filled('page');

        return view('blog.index', compact('featured', 'posts', 'showFeatured'));
    }

    public function show(BlogPost $post)
    {
        abort_unless($post->isPublished() || (auth()->user()?->isAdmin() ?? false), 404);

        $post->load(['author', 'category', 'businesses']);

        $related = BlogPost::published()
            ->with('category')
            ->whereKeyNot($post->getKey())
            ->when($post->blog_category_id, fn ($q) => $q->where('blog_category_id', $post->blog_category_id))
            ->latest('published_at')
            ->take(3)
            ->get();

        // Fall back to newest posts if the category had none.
        if ($related->isEmpty()) {
            $related = BlogPost::published()->whereKeyNot($post->getKey())
                ->latest('published_at')->take(3)->get();
        }

        return view('blog.show', compact('post', 'related'));
    }
}

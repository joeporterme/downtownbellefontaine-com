<?php

namespace App\Http\Middleware;

use App\Models\Page;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class LoadCurrentPage
{
    /**
     * Resolve the CMS Page record for the current route (by route name) and
     * share it as $currentPage. Draft pages 404 for non-admins.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $name = $request->route()?->getName();
        $key = null;

        if ($name && str_starts_with($name, 'pages.')) {
            $key = substr($name, strlen('pages.'));
        } elseif ($request->path() === '/' || $request->path() === '') {
            $key = 'home';
        }

        if ($key) {
            $page = Page::forKey($key);

            if ($page) {
                if (! $page->isPublished() && ! (auth()->user()?->isAdmin() ?? false)) {
                    abort(404);
                }

                View::share('currentPage', $page);
            }
        }

        return $next($request);
    }
}

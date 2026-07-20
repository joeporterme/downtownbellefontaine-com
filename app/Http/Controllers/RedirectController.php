<?php

namespace App\Http\Controllers;

use App\Models\Redirect;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RedirectController extends Controller
{
    /**
     * Fallback handler: fires only when no other route matched (i.e. legacy
     * WordPress URLs). Looks the path up in the redirects table and issues a
     * 301/302, a 410, or falls through to a normal 404.
     */
    public function handle(Request $request)
    {
        $path = Redirect::normalize($request->path());
        $rule = Redirect::resolve($path);

        if ($rule === null) {
            abort(404);
        }

        $this->recordHit($rule['id']);

        // 410 Gone (and any non-redirect status) — no target.
        if ($rule['status'] === 410 || blank($rule['to'])) {
            abort($rule['status'] === 410 ? 410 : 404);
        }

        $target = $rule['to'];

        // Preserve the original query string on the redirect (unless the target
        // already carries its own).
        $query = $request->getQueryString();
        if ($query && ! str_contains($target, '?')) {
            $target .= '?'.$query;
        }

        return redirect()->to($target, $rule['status']);
    }

    /**
     * Increment the hit counter without busting the cached matching map
     * (hits are not part of the map) or disturbing updated_at.
     */
    protected function recordHit(int $id): void
    {
        try {
            DB::table('redirects')
                ->where('id', $id)
                ->update([
                    'hits' => DB::raw('hits + 1'),
                    'last_hit_at' => now(),
                ]);
        } catch (\Throwable $e) {
            // Never let analytics bookkeeping break the redirect itself.
        }
    }
}

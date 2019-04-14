<?php

namespace Modules\KB\Http\Controllers;

use App\Tag;
use App\Http\Controllers\Controller;

use Modules\KB\Entities\WikiArticle;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

use OwenIt\Auditing\Models\Audit;

class SearchController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        $this->authorize('list', WikiArticle::class);

        // If previous route is from other module, forget search string
        if (!Str::startsWith(previous_route(), 'kb.')) {
            $request->session()->forget('kb_search');
        }

        // Handle search session persistence
        if ($request->has('reset_search') || ($request->has('search') && $request->search == null)) {
            $request->session()->forget('kb_search');
            return redirect()->route('kb.index');
        }
        if (isset($request->search)) {
            $request->session()->put('kb_search', $request->search);
        }

        // Init query
        $query = WikiArticle::query();

        // Search results
        if ($request->session()->has('kb_search')) {
            $search = $request->session()->get('kb_search');
            $query->where(function($wq) use($search) {
                return $wq->where('title', 'LIKE', '%' . $search . '%')
                    ->orWhere('content', 'LIKE', '%' . $search . '%');
            });
        } else {
            $search = null;
        }        

        return view('kb::index', [
            'article_results' => $query
                ->orderBy('title')
                ->paginate(50),
            'search' => $search,
            'popular_articles' => WikiArticle::leftJoin('kb_article_views', function($join){
                    $join->on('kb_article_views.viewable_id', '=', 'wiki_articles.id')
                        ->where('kb_article_views.viewable_type', WikiArticle::class);
                })
                ->orderBy('kb_article_views.value', 'desc')
                ->select('wiki_articles.*')
                ->limit(5)                
                ->get(),
            'recent_articles' => WikiArticle::orderBy('updated_at', 'desc')
                ->limit(5)
                ->get(),
            'popular_tags' => Tag::orderBy('name')
                ->get()
                ->map(function($t){
                    return [
                        'tag' => $t,
                        'count' => $t->wikiArticles()->count(),
                    ];
                })
                ->filter(function($t){
                    return $t['count'] > 0;
                })
                ->sortByDesc('count')
                ->pluck('tag')
                ->slice(0, 25)
                ->sortBy('name', SORT_NATURAL|SORT_FLAG_CASE),
        ]);
    }
  
    public function latestChanges() {
        $this->authorize('list', WikiArticle::class);

        return view('kb::latest_changes', [
            'audits' =>  Audit::where('auditable_type', WikiArticle::class)
                ->orderBy('created_at', 'DESC')
                ->paginate(),
        ]);
    }

}
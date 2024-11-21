<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;

class ApiArticleController extends Controller
{
    // REST API
    public function index(Request $request)
    {

        $query = Article::query();

        // Optional search filters
        if ($request->filled('keyword')) {
            $query->where('title', 'LIKE', '%' . $request->keyword . '%')
                ->orWhere('content', 'LIKE', '%' . $request->keyword . '%');
        }

        if ($request->filled('published_date')) {
            $query->whereDate('published_at', $request->published_date);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('source')) {
            $query->where('source', $request->source);
        }

        // Pagination applied here
        $articles = $query->paginate(10); // Adjust '10' to control the number of results per page
        return response()->json([
            'status' => 200,
            'articles' => $articles,
            'message' => 'Articles fetched successfully'
        ], 200);

        return response()->json($articles, 200);
    }


    public function show($id)
    {
        $article = Article::find($id);

        if (!$article) {
            return response()->json(['error' => 'Article not found'], 404);
        }

        return response()->json([
            'status' => 200,
            'article' => $article,
            'message' => 'Article fetched successfully'
        ]);

        return response()->json($article, 200);
    }
}

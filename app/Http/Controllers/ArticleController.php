<?php

namespace App\Http\Controllers;

use App\Services\NewsAggregatorService;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    protected $newsAggregatorService;

    public function __construct(NewsAggregatorService $newsAggregatorService)
    {
        $this->newsAggregatorService = $newsAggregatorService;
    }

    public function fetchArticles()
    {
        // Fetch and store articles from multiple sources
        $this->newsAggregatorService->fetchArticles();

        return response()->json(['message' => 'Articles fetched and stored successfully']);
    }






}

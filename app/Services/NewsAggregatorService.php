<?php

namespace App\Services;

use App\Models\Article;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class NewsAggregatorService
{
    protected $newsApiKey = 'd57055dba1d3424f900469ec8526dd71';
    protected $guardianApiKey = 'YOUR_GUARDIAN_API_KEY';
    // protected $bbcApiKey = 'YOUR_BBC_API_KEY';
    protected $nyTimesApiKey = 'e18197e0-6e56-4c95-a787-75f15b978f5a';

    public function fetchArticlesFromNewsApi()
    {
        $url = "https://newsapi.org/v2/top-headlines?sources=techcrunch&apiKey={$this->newsApiKey}";
        $response = Http::get($url);
        return $response->json()['articles'];
    }

    public function fetchArticlesFromGuardian()
    {
        $url = "https://content.guardianapis.com/search?api-key={$this->guardianApiKey}";
        $response = Http::get($url);
        return $response->json()['response']['results'];
    }

    public function fetchArticlesFromBBC()
    {
        $url = "https://newsapi.org/v2/top-headlines?sources=bbc-news&apiKey={$this->newsApiKey}";
        $response = Http::get($url);
        // dd($url);
        return $response->json()['articles'];
    }

    public function fetchArticlesFromNewYorktimes()
    {
        $url = "https://developer.nytimes.com/my-apps/{$this->nyTimesApiKey}";
        $response = Http::get($url);
        return $response->json()['results'];
    }

    public function fetchArticles()
    {
        $articles = collect();


        // Fetch from NewsAPI
        $newsApiArticles = $this->fetchArticlesFromNewsApi();
        foreach ($newsApiArticles as $article) {
            $slug = $this->getSlugByCheckingUniqueNews($article);
            if (!isset($slug)) {
                continue;
            }
            $articles->push($this->transformArticle($article, 'NewsAPI', $slug));
        }

        // Fetch from BBC News
        $bbcArticles = $this->fetchArticlesFromBBC();
        foreach ($bbcArticles as $article) {
            $slug = $this->getSlugByCheckingUniqueNews($article);
            if (!isset($slug)) {
                continue;
            }
            $articles->push($this->transformArticle($article, 'BBC News', $slug));
        }

        // dd($articles);
        // Fetch from New York times News
        // $nyTimesArticles = $this->fetchArticlesFromNewYorktimes();
        // foreach ($nyTimesArticles as $article) {
        //     $articles->push($this->transformArticle($article, 'New York Times'));
        // }


        // Fetch from The Guardian
        // $guardianArticles = $this->fetchArticlesFromGuardian();
        // foreach ($guardianArticles as $article) {
        //     $articles->push($this->transformArticle($article, 'The Guardian'));
        // }

        // Store in database
        foreach ($articles as $article) {
            Article::updateOrCreate(
                ['title' => $article['title']], // Prevent duplicates by title
                $article
            );
        }
    }


    public function getSlugByCheckingUniqueNews($article)
    {
        $date = Carbon::parse($article['publishedAt']);
        $year = $date->year;
        $month = $date->format('m');
        $day = $date->format('d');
        $slugPrefix = "{$year}/{$month}/{$day}/";

        // Remove the trailing slash if it exists
        $url = rtrim($article['url'], '/');
        // Extract the last part of the URL
        $slug = $slugPrefix . basename($url);
        // $slug = Article::generateUniqueSlug(basename($url));
        // dd($slug );
        $existingArticle = Article::where('slug', $slug)->first();
        if ($existingArticle) {
            return null;
        }
        return $slug;
    }

    private function transformArticle($article, $source, $slug)
    {
        // Convert to MySQL-compatible format
        $mysqlDate = Carbon::parse($article['publishedAt'])->format('Y-m-d H:i:s');

        return [
            'title' => $article['title'] ?? '',
            'content' => $article['description'] ?? '',
            'author' => $article['author'] ?? 'Unknown',
            'source' => $source,
            'url' => $article['url'] ?? null,
            'image_url' => $article['urlToImage'] ?? null,
            'slug' => $slug,
            'category' => $article['category'] ?? 'General',
            'published_at' => $mysqlDate ?? now(),
        ];
    }
}

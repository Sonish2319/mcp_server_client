<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use App\Services\ArticleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function __construct(
        private readonly ArticleService $articleService
    ) {
    }

    public function index(Request $request)
    {
        $perPage = min(
            max((int) $request->integer('per_page', 10), 1),
            100
        );

        $articles = $this->articleService->list($perPage);

        return ArticleResource::collection($articles);
    }

    public function show(Article $article)
    {
        return new ArticleResource($article);
    }

    public function store(StoreArticleRequest $request)
    {
        $article = $this->articleService->create(
            $request->validated()
        );

        return (new ArticleResource($article))
            ->response()
            ->setStatusCode(201);
    }

    public function update(
        UpdateArticleRequest $request,
        Article $article
    ) {
        $article = $this->articleService->update(
            $article,
            $request->validated()
        );

        return new ArticleResource($article);
    }

    public function destroy(Article $article): JsonResponse
    {
        $this->articleService->delete($article);

        return response()->json([
            'message' => 'Article deleted successfully.',
        ]);
    }

    public function search(Request $request)
    {
        $validated = $request->validate([
            'q' => [
                'required',
                'string',
                'min:2',
                'max:100',
            ],
        ]);

        $perPage = min(
            max((int) $request->integer('per_page', 10), 1),
            100
        );

        $articles = $this->articleService->search(
            $validated['q'],
            $perPage
        );

        return ArticleResource::collection($articles);
    }
}
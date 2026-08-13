<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use App\Models\Article;
use App\Services\ArticleService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function __construct(
        private readonly ArticleService $articleService
    ) {
    }

    public function index(Request $request): View
    {
        $search = trim($request->query('q', ''));

        if ($search !== '') {
            $articles = $this->articleService->search($search, 10);
        } else {
            $articles = $this->articleService->list(10);
        }

        return view('blog.index', [
            'articles' => $articles,
            'search' => $search,
        ]);
    }

    public function show(Article $article): View
    {
        return view('blog.show', [
            'article' => $article,
        ]);
    }

    public function create(): View
    {
        return view('blog.create');
    }

    public function store(StoreArticleRequest $request): RedirectResponse
    {
        $article = $this->articleService->create(
            $request->validated()
        );

        return redirect()
            ->route('blog.show', $article)
            ->with('success', 'Article created successfully.');
    }

    public function edit(Article $article): View
    {
        return view('blog.edit', [
            'article' => $article,
        ]);
    }

    public function update(
        UpdateArticleRequest $request,
        Article $article
    ): RedirectResponse {
        $this->articleService->update(
            $article,
            $request->validated()
        );

        return redirect()
            ->route('blog.show', $article)
            ->with('success', 'Article updated successfully.');
    }

    public function destroy(Article $article): RedirectResponse
    {
        $this->articleService->delete($article);

        return redirect()
            ->route('blog.index')
            ->with('success', 'Article deleted successfully.');
    }
}
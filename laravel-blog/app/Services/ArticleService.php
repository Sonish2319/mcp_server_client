<?php

namespace App\Services;

use App\Models\Article;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ArticleService
{
    public function list(int $perPage = 10): LengthAwarePaginator
    {
        return Article::latest()->paginate($perPage);
    }

    public function find(int $id): Article
    {
         return Article::findOrFail($id);
    }

    public function create(array $data): Article
    {
        return Article::create($data);
    }

    public function update(Article $article, array $data): Article
    {
        $article->update($data);

        return $article->fresh();
    }

    public function delete(Article $article): void
    {
        $article->delete();
    }

    public function search(
        string $query,
        int $perPage = 10
    ): LengthAwarePaginator {
        return Article::query()
            ->where(function ($builder) use ($query) {
                $builder
                    ->where('title', 'like', "%{$query}%")
                    ->orWhere('content', 'like', "%{$query}%")
                    ->orWhere('author', 'like', "%{$query}%");
            })
            ->latest()
            ->paginate($perPage);
    }
}
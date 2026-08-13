<?php

namespace App\Mcp\Tools;

use App\Services\ArticleService;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Tool;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[Description('Search blog posts by title, content, or author.')]
#[IsReadOnly]
class SearchPostsTool extends Tool
{
    protected string $name = 'search_posts';

    protected string $description =
        'Search blog posts by title, content, or author.';

    public function __construct(
        private readonly ArticleService $articleService
    ) {
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'query' => $schema
                ->string()
                ->description('The search term to find matching blog posts.')
                // ->minLength(2)
                // ->maxLength(100)
                ->required(),

            'limit' => $schema
                ->integer()
                ->description('Maximum number of matching posts to return.')
                ->default(10),
        ];
    }

    public function handle(Request $request): Response
    {
        $validated = $request->validate([
            'query' => [
                'required',
                'string',
                'min:2',
                'max:100',
            ],
            'limit' => [
                'nullable',
                'integer',
                'min:1',
                'max:50',
            ],
        ]);

        $query = trim($validated['query']);

        $limit = isset($validated['limit'])
            ? (int) $validated['limit']
            : 10;

        $articles = $this->articleService->search(
            $query,
            $limit
        );

        $posts = collect($articles->items())
            ->map(function ($article) {
                return [
                    'id' => $article->id,
                    'title' => $article->title,
                    'author' => $article->author,
                    'created_at' => $article->created_at?->toISOString(),
                ];
            })
            ->values()
            ->all();

        return Response::text(
            json_encode([
                'query' => $query,
                'posts' => $posts,
                'count' => count($posts),
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
        );
    }
}
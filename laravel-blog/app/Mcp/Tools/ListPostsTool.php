<?php

namespace App\Mcp\Tools;

use App\Services\ArticleService;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Tool;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[IsReadOnly]
class ListPostsTool extends Tool
{
    protected string $name = 'list_posts';

    protected string $description = 'List blog posts from the Laravel blog.';

    public function __construct(
        private readonly ArticleService $articleService
    ) {
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'limit' => $schema
                ->integer()
                ->description('Maximum number of posts to return.')
                ->default(10),
        ];
    }

    public function handle(Request $request): Response
    {

    $user = $request->user();

    if (! $user || ! $user->can('post.view')) {
        return Response::text(
            json_encode([
                'success' => false,
                'message' => 'You are not authorized to view blog posts.',
            ], JSON_UNESCAPED_SLASHES)
        );
    }

        $limit = (int) $request->get('limit', 10);

        $limit = min(
            max($limit, 1),
            50
        );

        $articles = $this->articleService->list($limit);

        $posts = collect($articles->items())
            ->map(function ($article) {
                return [
                    'id' => $article->id,
                    'title' => $article->title,
                    'author' => $article->author,
                    'content' => $article->content,
                    'created_at' => $article->created_at?->toISOString(),
                ];
            })
            ->values()
            ->all();

        return Response::text(
            json_encode(
                [
                    'posts' => $posts,
                    'count' => count($posts),
                ],
                JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
            )
        );
    }
}
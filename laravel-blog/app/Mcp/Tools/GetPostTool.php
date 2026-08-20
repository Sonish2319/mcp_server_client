<?php

namespace App\Mcp\Tools;

use App\Services\ArticleService;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Tool;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[Description('Get a single blog post by its ID.')]
#[IsReadOnly]
class GetPostTool extends Tool
{
    protected string $name = 'get_post';

    protected string $description = 'Retrieve a single blog post by its ID.';

    public function __construct(
        private readonly ArticleService $articleService
    ) {
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'id' => $schema
                ->integer()
                ->description('The ID of the blog post.')
                ->required(),
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

        $validated = $request->validate([
            'id' => [
                'required',
                'integer',
                'min:1',
            ],
        ]);

        try {
            $article = $this->articleService->find(
                $validated['id']
            );
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException) {
            return Response::error(
                "Blog post with ID {$validated['id']} was not found."
            );
        }

        return Response::text(
            json_encode([
                'id' => $article->id,
                'title' => $article->title,
                'content' => $article->content,
                'author' => $article->author,
                'created_at' => $article->created_at?->toISOString(),
                'updated_at' => $article->updated_at?->toISOString(),
            ], 
            JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
        );
    }
}
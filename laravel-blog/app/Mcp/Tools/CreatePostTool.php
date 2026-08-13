<?php

namespace App\Mcp\Tools;

use App\Services\ArticleService;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Tool;
use Laravel\Mcp\Server\Attributes\Description;

#[Description('Create a new blog post.')]
class CreatePostTool extends Tool
{
    protected string $name = 'create_post';

    protected string $description =
        'Create a new blog post with a title, content, and author.';

    public function __construct(
        private readonly ArticleService $articleService
    ) {
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'title' => $schema
                ->string()
                ->description('The title of the blog post.')
                // ->minLength(3)
                // ->maxLength(255)
                ->required(),

            'content' => $schema
                ->string()
                ->description('The full content of the blog post.')
                // ->minLength(1)
                // ->maxLength(50000)
                ->required(),

            'author' => $schema
                ->string()
                ->description('The author of the blog post.')
                // ->minLength(2)
                // ->maxLength(100)
                ->required(),
        ];
    }

    public function handle(Request $request): Response
    {
        $validated = $request->validate([
            'title' => [
                'required',
                'string',
                'min:3',
                'max:255',
            ],
            'content' => [
                'required',
                'string',
                'min:1',
                'max:50000',
            ],
            'author' => [
                'required',
                'string',
                'min:2',
                'max:100',
            ],
        ]);

        $article = $this->articleService->create([
            'title' => trim($validated['title']),
            'content' => trim($validated['content']),
            'author' => trim($validated['author']),
        ]);

        return Response::text(
            json_encode([
                'success' => true,
                'message' => 'Blog post created successfully.',
                'post' => [
                    'id' => $article->id,
                    'title' => $article->title,
                    'content' => $article->content,
                    'author' => $article->author,
                    'created_at' => $article->created_at?->toISOString(),
                ],
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
        );
    }
}
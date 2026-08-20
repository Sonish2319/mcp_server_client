<?php

namespace App\Mcp\Tools;

use App\Services\ArticleService;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Tool;
use Laravel\Mcp\Server\Attributes\Description;

#[Description('Update an existing blog post.')]
class UpdatePostTool extends Tool
{
    protected string $name = 'update_post';

    protected string $description =
        'Update the title, content, or author of an existing blog post.';

    public function __construct(
        private readonly ArticleService $articleService
    ) {
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'id' => $schema
                ->integer()
                ->description('The ID of the blog post to update.')
                ->required(),

            'title' => $schema
                ->string()
                ->description('The new title.'),
                // ->minLength(3)
                // ->maxLength(255),

            'content' => $schema
                ->string()
                ->description('The new content.'),
                // ->minLength(1)
                // ->maxLength(50000),

            'author' => $schema
                ->string()
                ->description('The new author.')
                // ->minLength(2)
                // ->maxLength(100),
        ];
    }

    public function handle(Request $request): Response
    {

        $user = $request->user();

    if (! $user || ! $user->can('post.update')) {
        return Response::text(
            json_encode([
                'success' => false,
                'message' => 'You are not authorized to update blog posts.',
            ], JSON_UNESCAPED_SLASHES)
        );
    }
    
        $validated = $request->validate([
            'id' => [
                'required',
                'integer',
                'min:1',
            ],

            'title' => [
                'sometimes',
                'string',
                'min:3',
                'max:255',
            ],

            'content' => [
                'sometimes',
                'string',
                'min:1',
                'max:50000',
            ],

            'author' => [
                'sometimes',
                'string',
                'min:2',
                'max:100',
            ],
        ]);

        $id = (int) $validated['id'];

        try {
            $article = $this->articleService->find($id);
        } catch (ModelNotFoundException) {
            return Response::error(
                "Blog post with ID {$id} was not found."
            );
        }

        $updates = [];

        if (array_key_exists('title', $validated)) {
            $updates['title'] = trim($validated['title']);
        }

        if (array_key_exists('content', $validated)) {
            $updates['content'] = trim($validated['content']);
        }

        if (array_key_exists('author', $validated)) {
            $updates['author'] = trim($validated['author']);
        }

        if ($updates === []) {
            return Response::error(
                'At least one field must be provided for update.'
            );
        }

        $article = $this->articleService->update(
            $article,
            $updates
        );

        return Response::text(
            json_encode([
                'success' => true,
                'message' => 'Blog post updated successfully.',
                'post' => [
                    'id' => $article->id,
                    'title' => $article->title,
                'content' => $article->content,
                'author' => $article->author,
                'created_at' => $article->created_at?->toISOString(),
                'updated_at' => $article->updated_at?->toISOString(), 
            ],
           ],
            JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
        );
    }
}
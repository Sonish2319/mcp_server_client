<?php

namespace App\Mcp\Tools;

use App\Services\ArticleService;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Tool;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tools\Annotations\IsDestructive;

#[Description('Delete an existing blog post.')]
#[IsDestructive]
class DeletePostTool extends Tool
{
    protected string $name = 'delete_post';

    protected string $description =
        'Permanently delete a blog post by its ID.';

    public function __construct(
        private readonly ArticleService $articleService
    ) {
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'id' => $schema
                ->integer()
                ->description('The ID of the blog post to delete.')
                ->required(),
        ];
    }

    public function handle(Request $request): Response
    {
        $user = $request->user();

        if (! $user || ! $user->can('post.delete')) {
        return Response::text(
            json_encode([
                'success' => false,
                'message' => 'You are not authorized to delete blog posts.',
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

        $id = (int) $validated['id'];

        try {
            $article = $this->articleService->find($id);
        } catch (ModelNotFoundException) {
            return Response::error(
                "Blog post with ID {$id} was not found."
            );
        }

        $this->articleService->delete($article);

        return Response::text(
            json_encode([
                'success' => true,
                'message' => 'Blog post deleted successfully.',
                'deleted_post_id' => $id,
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
        );
    }
}
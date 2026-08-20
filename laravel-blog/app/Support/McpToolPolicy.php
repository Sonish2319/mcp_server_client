<?php

namespace App\Support;

class McpToolPolicy
{
    public static function permission(string $tool): ?string
    {
        return match ($tool) {
            'list_posts',
            'get_post',
            'search_posts' => 'post.view',

            'create_post' => 'post.create',
            'update_post' => 'post.update',
            'delete_post' => 'post.delete',

            default => null,
        };
    }
}
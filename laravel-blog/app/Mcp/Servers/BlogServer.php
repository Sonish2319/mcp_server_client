<?php

namespace App\Mcp\Servers;

use Laravel\Mcp\Server;
use Laravel\Mcp\Server\Attributes\Instructions;
use Laravel\Mcp\Server\Attributes\Name;
use Laravel\Mcp\Server\Attributes\Version;
use App\Mcp\Tools\ListPostsTool;
use App\Mcp\Tools\GetPostTool;
use App\Mcp\Tools\SearchPostsTool;
use App\Mcp\Tools\CreatePostTool;
use App\Mcp\Tools\UpdatePostTool;
use App\Mcp\Tools\DeletePostTool;

#[Name('Blog Server')]
#[Version('0.0.1')]
#[Instructions('Instructions describing how to use the server and its features.')]
class BlogServer extends Server
{
    protected array $tools = [
        ListPostsTool::class,
        GetPostTool::class,
        SearchPostsTool::class,
        CreatePostTool::class,
        UpdatePostTool::class,
        DeletePostTool::class,
    ];

    protected array $resources = [
        //
    ];

    protected array $prompts = [
        //
    ];
}

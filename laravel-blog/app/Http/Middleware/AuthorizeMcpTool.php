<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Support\McpToolPolicy;
use Symfony\Component\HttpFoundation\Response;

class AuthorizeMcpTool
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        Log::info('MCP Authorization Request', [
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'user_id' => $user?->id,
            'user_email' => $user?->email,
            'content_type' => $request->header('Content-Type'),
            'authorization_present' => $request->hasHeader('Authorization'),
            'body' => $request->all(),
            'raw_body' => $request->getContent(),
        ]);

        if (! $user) {

            Log::warning('MCP Authorization: Unauthenticated');

            return response()->json([
                'message' => 'Unauthenticated.',
            ], 401);
        }

        $tool = $request->input('tool');

        Log::info('MCP Authorization Tool', [
            'tool' => $tool,
        ]);

        $permission = McpToolPolicy::permission($tool);

        Log::info('MCP Authorization Permission', [
            'tool' => $tool,
            'permission' => $permission,
        ]);

        if (! $permission) {

            Log::warning('MCP Authorization: Unknown tool', [
                'tool' => $tool,
            ]);

            return response()->json([
                'message' => 'Unknown MCP tool.',
            ], 403);
        }

        if (! $user->can($permission)) {

            Log::warning('MCP Authorization: Permission denied', [
                'user_id' => $user->id,
                'tool' => $tool,
                'permission' => $permission,
            ]);

            return response()->json([
                'message' => 'You are not authorized to use this tool.',
                'tool' => $tool,
                'required_permission' => $permission,
            ], 403);
        }

        Log::info('MCP Authorization: Permission granted', [
            'user_id' => $user->id,
            'tool' => $tool,
            'permission' => $permission,
        ]);

        return $next($request);
    }
}
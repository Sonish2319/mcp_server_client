TOOLS = [
    {
        "type": "function",
        "function": {
            "name": "search_posts",
            "description": (
                "Search blog posts by title, content, or author."
            ),
            "parameters": {
                "type": "object",
                "properties": {
                    "query": {
                        "type": "string",
                        "description": (
                            "The search term to find matching blog posts."
                        ),
                    },
                    "limit": {
                        "type": "integer",
                        "description": (
                            "Maximum number of posts to return."
                        ),
                    },
                },
                "required": ["query"],
            },
        },
    },
    {
        "type": "function",
        "function": {
            "name": "get_post",
            "description": (
                "Retrieve a single blog post by its ID."
            ),
            "parameters": {
                "type": "object",
                "properties": {
                    "id": {
                        "type": "integer",
                        "description": (
                            "The ID of the blog post."
                        ),
                    },
                },
                "required": ["id"],
            },
        },
    },
]
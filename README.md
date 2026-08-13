# mcp_server_client
A local AI chatbot using Django, Ollama, Qwen, and MCP integrates with a Laravel 12 blog. Django acts as the MCP client, while Laravel provides tools for blog operations. The LLM decides when tools are needed, and Laravel handles requests using services, Eloquent, and MySQL.



# architecture

                                      USER
                                        │
                                        │
                                        │
                    "Find me a post related to Kubernetes"
                                        │
                                        ▼
┌─────────────────────────────────────────────────────────────────────────────┐
│                              DJANGO PROJECT                                 │
│                                                                             │
│                                                                             │
│  ┌───────────────────────────────────────────────────────────────────────┐  │
│  │ chatbot/management/commands/chat.py                                   │  │
│  │                                                                       │  │
│  │ CLI                                                                   │  │
│  │                                                                       │  │
│  │ • Reads user prompt                                                   │  │
│  │ • Prints response                                                     │  │
│  └────────────────────────────────┬──────────────────────────────────────┘  │
│                                   │                                         │
│                                   ▼                                         │
│  ┌───────────────────────────────────────────────────────────────────────┐  │
│  │ chatbot/services/chat.py                                              │  │
│  │                                                                       │  │
│  │ ChatService                                                           │  │
│  │                                                                       │  │
│  │ • Conversation management                                             │  │
│  │ • Calls Ollama                                                        │  │
│  │ • Detects tool calls                                                  │  │
│  │ • Calls MCP client                                                    │  │
│  │ • Sends tool result back to Ollama                                    │  │
│  └──────────────────────┬──────────────────────┬─────────────────────────┘  │
│                         │                      │                            │
│                         │                      │                            │
│                         ▼                      ▼                            │
│  ┌─────────────────────────────┐   ┌─────────────────────────────────────┐ │
│  │ services/ollama.py          			│   │ services/mcp_client.py              │ │
│  │                             			│   │                                     │ │
│  │ Ollama HTTP client          			│   │ MCP Client                          │ │
│  │                             			│   │                                     │ │
│  │ • send messages             			│   │ • tools/list                        │ │
│  │ • send tools                			│   │ • tools/call                        │ │
│  │ • receive LLM response     				│   │ • Laravel communication              │ │
│  └──────────────┬──────────────┘   └──────────────────┬──────────────────┘ │
│                 │                                     │                     │
│                 │                                     │                     │
│                 │                                     │                     │
│                 │                                     │ MCP                 │
└─────────────────┼─────────────────────────────────────┼─────────────────────┘
                  │                                     │
                  │ HTTP                                │
                  ▼                                     ▼
        ┌──────────────────┐              ┌──────────────────────────────────┐
        │      OLLAMA      │              │       LARAVEL MCP SERVER         │
        │                  │              │                                  │
        │ localhost:11434  │              │                                  │
        └────────┬─────────┘              │  MCP Tool Registry               │
                 │                        │                                  │
                 ▼                        │  ┌────────────────────────────┐  │
        ┌──────────────────┐              │  │ list_posts                 │  │
        │       QWEN       │              │  │ → ListPostsTool.php        │  │
        │                  │              │  │                            │  │
        │      LOCAL       │              │  │ get_post                   │  │
        │       LLM        │              │  │ → GetPostTool.php          │  │
        │                  │              │  │                            │  │
        └────────┬─────────┘              │  │ search_posts               │  │
                 │                        │  │ → SearchPostsTool.php      │  │
                 │                        │  │                            │  │
                 │ Tool Call              │  │ create_post                │  │
                 │                        │  │ → CreatePostTool.php       │  │
                 │                        │  │                            │  │
                 │ search_posts           │  │ update_post                │  │
                 │ query=Kubernetes       │  │ → UpdatePostTool.php       │  │
                 │                        │  │                            │  │
                 ▼                        │  │ delete_post                │  │
        ┌──────────────────┐              │  │ → DeletePostTool.php       │  │
        │    ChatService   │              │  └────────────────────────────┘  │
        │                  │              │                                  │
        └────────┬─────────┘        ──────────────┬───────────────────┘
                 │                                       │
                 │                                       │
                 │ call_tool()                           │
                 ▼                                       ▼
        ┌──────────────────┐              ┌─────────────────────────────┐
        │   mcp_client.py  │ ───── MCP ──────► │ SearchPostsTool.php      
        │                  │                    │                             
        │ tools/call       │                    │ app/Mcp/Tools/              
        └──────────────────┘                    │ SearchPostsTool.php         
                                                │                             
                                                │ • validate input            
                                                │ • execute tool              
                                                │ • format response            
                                                └──────────────┬──────────────┘
                                                               │
                                                               │
                                                               ▼
                                                ┌─────────────────────────────┐
                                                │ ArticleService.php           			│
                                                │                             			│
                                                │ app/Services/                			│
                                                │ ArticleService.php           			│
                                                │                             			│
                                                │ • search logic              			│
                                                │ • application/business logic			│
                                                └──────────────┬──────────────┘
                                                               │
                                                               ▼
                                                ┌─────────────────────────────┐
                                                │ Article.php                 			│
                                                │                             			│
                                                │ app/Models/Article.php      			│
                                                │                             			│
                                                │ Eloquent ORM                			│
                                                └──────────────┬──────────────┘
                                                               │
                                                               ▼
                                                ┌─────────────────────────────┐
                                                │            MySQL             			│
                                                │                             			│
                                                │         articles            			│
                                                │                             			│
                                                │ id                          			│
                                                │ title                       			│
                                                │ content                     			│
                                                │ author                      			│
                                                │ created_at                  			│
                                                │ updated_at                  			│
                                                └──────────────┬──────────────┘
                                                               │
                                                               │ Query result
                                                               ▼
                                                ┌─────────────────────────────┐
                                                │ SearchPostsTool.php         			│
                                                └──────────────┬──────────────┘
                                                               │
                                                               │ MCP result
                                                               ▼
                                                ┌─────────────────────────────┐
                                                │ Django MCP Client            			│
                                                │ services/mcp_client.py      			│
                                                └──────────────┬──────────────┘
                                                               │
                                                               ▼
                                                ┌─────────────────────────────┐
                                                │ services/mcp_result.py      			│
                                                │                             			│
                                                │ MCP result → JSON/text      			│
                                                └──────────────┬──────────────┘
                                                               │
                                                               │
                                                               ▼
                                                ┌─────────────────────────────┐
                                                │ ChatService                 			│
                                                │ services/chat.py            			│
                                                └──────────────┬──────────────┘
                                                               │
                                                               │ Tool result
                                                               │
                                                               ▼
                                                        ┌───────────────┐
                                                        │    Ollama     		│
                                                        │      ↓        		│
                                                        │     Qwen      		│
                                                        └───────┬───────┘
                                                                │
                                                                │ Final answer
                                                                ▼
                                                        ┌───────────────┐
                                                        │   ChatService 		│
                                                        └───────┬───────┘
                                                                │
                                                                ▼
                                                        ┌───────────────┐
                                                        │    chat.py    		│
                                                        └───────┬───────┘
                                                                │
                                                                ▼
                                                               USER
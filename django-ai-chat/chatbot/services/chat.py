import asyncio

from chatbot.services.mcp_client import MCPClient
from chatbot.services.mcp_result import (
    serialize_mcp_result,
)
from chatbot.services.ollama import OllamaService
from chatbot.services.tool_adapter import (
    MCPToolAdapter,
)


class ChatService:

    SYSTEM_PROMPT = (
        "You are a helpful assistant. "
        "Answer general knowledge questions directly "
        "using your own knowledge. "
        "Use the available tools only when the user "
        "asks about blog posts or needs information "
        "from the blog database. "
        "Do not use tools for general questions."
    )

    def __init__(
        self,
        mcp_url: str,
        model: str = "qwen3:4b",
    ):
        self.ollama = OllamaService(
            model=model
        )

        self.mcp = MCPClient(
            mcp_url
        )

        self.messages = [
            {
                "role": "system",
                "content": self.SYSTEM_PROMPT,
            }
        ]

        self.ollama_tools = []

    def initialize(self):
        """
        Discover MCP tools and convert them
        into Ollama-compatible tool definitions.
        """

        mcp_tools = asyncio.run(
            self.mcp.list_tools()
        )

        self.ollama_tools = (
            MCPToolAdapter.to_ollama_tools(
                mcp_tools
            )
        )

        return self.ollama_tools

    def ask(self, prompt: str) -> str:

        self.messages.append({
            "role": "user",
            "content": prompt,
        })

        while True:

            result = self.ollama.chat(
                self.messages,
                tools=self.ollama_tools,
            )

            message = result["message"]

            tool_calls = message.get(
                "tool_calls"
            )

            # -------------------------------------------------
            # No tool requested.
            # -------------------------------------------------

            if not tool_calls:

                content = message.get(
                    "content",
                    "",
                )

                self.messages.append({
                    "role": "assistant",
                    "content": content,
                })

                return content

            # -------------------------------------------------
            # LLM requested one or more tools.
            # -------------------------------------------------

            self.messages.append(message)

            for tool_call in tool_calls:

                function = (
                    tool_call["function"]
                )

                tool_name = (
                    function["name"]
                )

                arguments = (
                    function["arguments"]
                )

                print(
                    f"\n[MCP] Calling "
                    f"{tool_name}({arguments})"
                )

                # -------------------------------------------------
                # Execute the actual MCP tool.
                # -------------------------------------------------

                mcp_result = asyncio.run(
                    self.mcp.call_tool(
                        tool_name,
                        arguments,
                    )
                )

                result_text = (
                    serialize_mcp_result(
                        mcp_result
                    )
                )

                print(
                    f"[MCP] Result: "
                    f"{result_text}"
                )

                # -------------------------------------------------
                # Give the tool result back to the LLM.
                # -------------------------------------------------

                self.messages.append({
                    "role": "tool",
                    "content": result_text,
                })
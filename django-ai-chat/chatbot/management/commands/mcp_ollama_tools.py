import asyncio

from django.core.management.base import BaseCommand

from chatbot.services.mcp_client import MCPClient
from chatbot.services.tool_adapter import MCPToolAdapter


class Command(BaseCommand):

    help = "Show Laravel MCP tools in Ollama format"

    def handle(self, *args, **options):

        client = MCPClient(
            "http://127.0.0.1:8000/mcp/blog"
        )

        tools = asyncio.run(
            client.list_tools()
        )

        ollama_tools = (
            MCPToolAdapter.to_ollama_tools(
                tools
            )
        )

        for tool in ollama_tools:

            self.stdout.write(
                f"\nTool: {tool['function']['name']}"
            )

            self.stdout.write(
                f"\nDescription: "
                f"{tool['function']['description']}"
            )

            self.stdout.write(
                f"\nParameters: "
                f"{tool['function']['parameters']}"
            )

            self.stdout.write("\n")
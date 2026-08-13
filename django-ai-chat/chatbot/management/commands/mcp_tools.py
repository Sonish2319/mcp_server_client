import asyncio

from django.core.management.base import BaseCommand

from chatbot.services.mcp_client import MCPClient


class Command(BaseCommand):
    help = "Discover tools from the Laravel MCP server"

    def handle(self, *args, **options):

        client = MCPClient(
            "http://127.0.0.1:8000/mcp/blog"
        )

        tools = asyncio.run(
            client.list_tools()
        )

        self.stdout.write(
            self.style.SUCCESS(
                "Tools discovered from Laravel MCP:"
            )
        )

        for tool in tools:
            self.stdout.write(
                f"\n- {tool.name}"
            )

            if tool.description:
                self.stdout.write(
                    f"  {tool.description}"
                )
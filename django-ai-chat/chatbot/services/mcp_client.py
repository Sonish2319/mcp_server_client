import httpx2

from mcp import ClientSession
from mcp.client.streamable_http import streamable_http_client
from mcp.shared.exceptions import MCPError


class MCPClient:

    def __init__(
        self,
        url: str,
        token: str,
    ):
        self.url = url
        self.token = token

    def _create_http_client(self):

        return httpx2.AsyncClient(
            headers={
                "Authorization": f"Bearer {self.token}",
                "Accept": "application/json",
            },
            timeout=httpx2.Timeout(
                30.0,
                read=300.0,
            ),
            follow_redirects=True,
        )

    async def list_tools(self):

        http_client = self._create_http_client()

        try:

            async with streamable_http_client(
                self.url,
                http_client=http_client,
            ) as (
                read_stream,
                write_stream,
            ):

                async with ClientSession(
                    read_stream,
                    write_stream,
                ) as session:

                    try:
                        await session.initialize()
                    except MCPError as exc:
                        print("\n[MCP ERROR]")
                        print(f"Code: {exc.code}")
                        print(f"Message: {exc.message}")
                        print(f"Data: {exc.data}")
                        raise

                    result = await session.list_tools()

                    return result.tools

        finally:

            await http_client.aclose()

    async def call_tool(
        self,
        name: str,
        arguments: dict,
    ):

        http_client = self._create_http_client()

        try:

            async with streamable_http_client(
                self.url,
                http_client=http_client,
            ) as (
                read_stream,
                write_stream,
            ):

                async with ClientSession(
                    read_stream,
                    write_stream,
                ) as session:

                    await session.initialize()

                    result = await session.call_tool(
                        name,
                        arguments,
                    )

                    return result

        finally:

            await http_client.aclose()
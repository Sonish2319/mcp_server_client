from mcp import ClientSession
from mcp.client.streamable_http import streamable_http_client


# class MCPClient:
#     def __init__(self, url: str):
#         self.url = url

#     async def list_tools(self):
#         async with streamable_http_client(
#             self.url
#         ) as (
#             read_stream,
#             write_stream,
#         ):
#             async with ClientSession(
#                 read_stream,
#                 write_stream,
#             ) as session:

#                 await session.initialize()

#                 result = await session.list_tools()

#                 return result.tools


# from mcp import ClientSession
# from mcp.client.streamable_http import streamablehttp_client


# class MCPClient:

#     def __init__(self, url: str):
#         self.url = url

#     async def list_tools(self):
#         async with streamable_http_client(
#             self.url
#         ) as (
#             read_stream,
#             write_stream,
#         ):
#             async with ClientSession(
#                 read_stream,
#                 write_stream,
#             ) as session:

#                 await session.initialize()

#                 result = await session.list_tools()

#                 return result.tools

#     async def call_tool(
#         self,
#         name: str,
#         arguments: dict,
#     ):
#         async with streamable_http_client(
#             self.url
#         ) as (
#             read_stream,
#             write_stream,
#             _,
#         ):
#             async with ClientSession(
#                 read_stream,
#                 write_stream,
#             ) as session:

#                 await session.initialize()

#                 result = await session.call_tool(
#                     name,
#                     arguments,
#                 )

#                 return result




class MCPClient:

    def __init__(self, url: str):
        self.url = url

    async def list_tools(self):
        async with streamable_http_client(
            self.url
        ) as (
            read_stream,
            write_stream,
        ):
            async with ClientSession(
                read_stream,
                write_stream,
            ) as session:

                await session.initialize()

                result = await session.list_tools()

                return result.tools

    async def call_tool(
        self,
        name: str,
        arguments: dict,
    ):
        async with streamable_http_client(
            self.url
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
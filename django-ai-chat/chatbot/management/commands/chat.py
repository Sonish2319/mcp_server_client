# from django.core.management.base import BaseCommand


# class Command(BaseCommand):
#     help = "Start the local AI chatbot"

#     def handle(self, *args, **options):
#         self.stdout.write(
#             self.style.SUCCESS("Local AI Chatbot")
#         )

#         self.stdout.write(
#             "Type 'exit' to quit.\n"
#         )

#         while True:
#             prompt = input("You: ").strip()

#             if prompt.lower() in {"exit", "quit"}:
#                 self.stdout.write("Goodbye!")
#                 break

#             if not prompt:
#                 continue

#             self.stdout.write(
#                 f"Received: {prompt}"
#             )


# from django.core.management.base import BaseCommand

# from chatbot.services.ollama import OllamaService


# class Command(BaseCommand):
#     help = "Start the local AI chatbot"

#     def handle(self, *args, **options):
#         self.stdout.write(
#             self.style.SUCCESS(
#                 "Local AI Chatbot"
#             )
#         )

#         self.stdout.write(
#             "Type 'exit' or 'quit' to leave.\n"
#         )

#         ollama = OllamaService()

#         while True:
#             try:
#                 prompt = input("You: ").strip()
#             except (KeyboardInterrupt, EOFError):
#                 self.stdout.write("\nGoodbye!")
#                 break

#             if prompt.lower() in {"exit", "quit"}:
#                 self.stdout.write("Goodbye!")
#                 break

#             if not prompt:
#                 continue

#             try:
#                 response = ollama.chat(prompt)

#                 self.stdout.write(
#                     f"\nAI: {response}\n"
#                 )

#             except Exception as exc:
#                 self.stdout.write(
#                     self.style.ERROR(
#                         f"Error communicating with Ollama: {exc}"
#                     )
#                 )

# from django.core.management.base import BaseCommand

# from chatbot.services.ollama import OllamaService


# class Command(BaseCommand):
#     help = "Start the local AI chatbot"

#     def handle(self, *args, **options):
#         self.stdout.write(
#             self.style.SUCCESS(
#                 "Local AI Chatbot"
#             )
#         )

#         self.stdout.write(
#             "Type 'exit' or 'quit' to leave.\n"
#         )

#         ollama = OllamaService()

#         messages = []

#         while True:
#             try:
#                 prompt = input("You: ").strip()
#             except (KeyboardInterrupt, EOFError):
#                 self.stdout.write("\nGoodbye!")
#                 break

#             if prompt.lower() in {"exit", "quit"}:
#                 self.stdout.write("Goodbye!")
#                 break

#             if not prompt:
#                 continue

#             messages.append({
#                 "role": "user",
#                 "content": prompt,
#             })

#             try:
#                 response = ollama.chat(messages)

#                 messages.append({
#                     "role": "assistant",
#                     "content": response,
#                 })

#                 self.stdout.write(
#                     f"\nAI: {response}\n"
#                 )

#             except Exception as exc:
#                 # Remove the user message if the request failed.
#                 messages.pop()

#                 self.stdout.write(
#                     self.style.ERROR(
#                         f"Error communicating with Ollama: {exc}"
#                     )
#                 )


# from django.core.management.base import BaseCommand

# from chatbot.services.ollama import OllamaService
# from chatbot.services.tools import TOOLS


# class Command(BaseCommand):
#     help = "Start the local AI chatbot"

#     def handle(self, *args, **options):

#         self.stdout.write(
#             self.style.SUCCESS(
#                 "Local AI Chatbot"
#             )
#         )

#         self.stdout.write(
#             "Type 'exit' or 'quit' to leave.\n"
#         )

#         ollama = OllamaService()

#         messages = []

#         while True:

#             try:
#                 prompt = input("You: ").strip()

#             except (KeyboardInterrupt, EOFError):
#                 self.stdout.write("\nGoodbye!")
#                 break

#             if prompt.lower() in {"exit", "quit"}:
#                 self.stdout.write("Goodbye!")
#                 break

#             if not prompt:
#                 continue

#             messages.append({
#                 "role": "user",
#                 "content": prompt,
#             })

#             try:

#                 result = ollama.chat(
#                     messages,
#                     tools=TOOLS,
#                 )

#                 message = result["message"]

#                 # Check whether the LLM wants to call a tool.
#                 if message.get("tool_calls"):

#                     for tool_call in message["tool_calls"]:

#                         function = tool_call["function"]

#                         tool_name = function["name"]
#                         arguments = function["arguments"]

#                         self.stdout.write(
#                             "\n"
#                             + self.style.WARNING(
#                                 "LLM requested tool:"
#                             )
#                         )

#                         self.stdout.write(
#                             f"  Tool: {tool_name}"
#                         )

#                         self.stdout.write(
#                             f"  Arguments: {arguments}\n"
#                         )

#                     # Store the assistant's tool request.
#                     messages.append(message)

#                     continue

#                 # Normal assistant response.
#                 content = message.get(
#                     "content",
#                     "",
#                 )

#                 messages.append({
#                     "role": "assistant",
#                     "content": content,
#                 })

#                 self.stdout.write(
#                     f"\nAI: {content}\n"
#                 )

#             except Exception as exc:

#                 messages.pop()

#                 self.stdout.write(
#                     self.style.ERROR(
#                         f"Error: {exc}"
#                     )
#                 )

# import asyncio

# from django.core.management.base import BaseCommand

# from chatbot.services.mcp_client import MCPClient
# from chatbot.services.ollama import OllamaService
# from chatbot.services.tool_adapter import MCPToolAdapter


# class Command(BaseCommand):

#     help = "Start the local AI chatbot"

#     MCP_URL = "http://127.0.0.1:8000/mcp/blog"

#     def handle(self, *args, **options):

#         self.stdout.write(
#             self.style.SUCCESS(
#                 "Local AI Chatbot"
#             )
#         )

#         self.stdout.write(
#             "Type 'exit' or 'quit' to leave.\n"
#         )

#         ollama = OllamaService()

#         mcp = MCPClient(
#             self.MCP_URL
#         )

#         # Discover tools from Laravel MCP.
#         mcp_tools = asyncio.run(
#             mcp.list_tools()
#         )

#         # Convert MCP tools to Ollama format.
#         ollama_tools = (
#             MCPToolAdapter.to_ollama_tools(
#                 mcp_tools
#             )
#         )

#         self.stdout.write(
#             self.style.SUCCESS(
#                 f"Loaded {len(ollama_tools)} MCP tools."
#             )
#         )

#         for tool in ollama_tools:
#             self.stdout.write(
#                 f"  - {tool['function']['name']}"
#             )

#         messages = [
#             {
#                 "role": "system",
#                 "content": (
#                     "You are a helpful assistant. "
#                     "Answer general knowledge questions directly "
#                     "using your own knowledge. "
#                     "Use the available tools only when the user "
#                     "asks about blog posts or needs information "
#                     "from the blog database. "
#                     "Do not use tools for general questions "
#                     "such as 'What is Laravel?' unless the user "
#                     "explicitly asks about a blog post."
#                 ),
#             }
#         ]

#         while True:

#             try:
#                 prompt = input("\nYou: ").strip()

#             except (KeyboardInterrupt, EOFError):

#                 self.stdout.write(
#                     "\nGoodbye!"
#                 )

#                 break

#             if prompt.lower() in {
#                 "exit",
#                 "quit",
#             }:

#                 self.stdout.write(
#                     "Goodbye!"
#                 )

#                 break

#             if not prompt:
#                 continue

#             messages.append({
#                 "role": "user",
#                 "content": prompt,
#             })

#             try:

#                 result = ollama.chat(
#                     messages,
#                     tools=ollama_tools,
#                 )

#                 message = result["message"]

#                 # LLM wants to call one or more tools.
#                 if message.get("tool_calls"):

#                     messages.append(message)

#                     for tool_call in message[
#                         "tool_calls"
#                     ]:

#                         function = (
#                             tool_call["function"]
#                         )

#                         tool_name = (
#                             function["name"]
#                         )

#                         arguments = (
#                             function["arguments"]
#                         )

#                         self.stdout.write(
#                             "\n"
#                             + self.style.WARNING(
#                                 "Tool requested"
#                             )
#                         )

#                         self.stdout.write(
#                             f"  Name: "
#                             f"{tool_name}"
#                         )

#                         self.stdout.write(
#                             f"  Arguments: "
#                             f"{arguments}"
#                         )

#                     continue

#                 # Normal response.
#                 content = message.get(
#                     "content",
#                     "",
#                 )

#                 messages.append({
#                     "role": "assistant",
#                     "content": content,
#                 })

#                 self.stdout.write(
#                     f"\nAI: {content}"
#                 )

#             except Exception as exc:

#                 messages.pop()

#                 self.stdout.write(
#                     self.style.ERROR(
#                         f"\nError: {exc}"
#                     )
#                 )


from django.core.management.base import BaseCommand

from chatbot.services.chat import ChatService


class Command(BaseCommand):

    help = "Start the local AI chatbot"

    MCP_URL = "http://127.0.0.1:8000/mcp/blog"

    def handle(self, *args, **options):

        self.stdout.write(
            self.style.SUCCESS(
                "Local AI Chatbot"
            )
        )

        self.stdout.write(
            "Type 'exit' or 'quit' to leave.\n"
        )

        chat = ChatService(
            mcp_url=self.MCP_URL,
        )

        try:

            tools = chat.initialize()

        except Exception as exc:

            self.stdout.write(
                self.style.ERROR(
                    f"Failed to connect to MCP server: "
                    f"{exc}"
                )
            )

            return

        self.stdout.write(
            self.style.SUCCESS(
                f"Loaded {len(tools)} MCP tools."
            )
        )

        for tool in tools:

            self.stdout.write(
                f"  - "
                f"{tool['function']['name']}"
            )

        while True:

            try:

                prompt = input("\nYou: ").strip()

            except (
                KeyboardInterrupt,
                EOFError,
            ):

                self.stdout.write(
                    "\nGoodbye!"
                )

                break

            if prompt.lower() in {
                "exit",
                "quit",
            }:

                self.stdout.write(
                    "Goodbye!"
                )

                break

            if not prompt:
                continue

            try:

                response = chat.ask(
                    prompt
                )

                self.stdout.write(
                    f"\nAI: {response}"
                )

            except Exception as exc:

                self.stdout.write(
                    self.style.ERROR(
                        f"\nError: {exc}"
                    )
                )
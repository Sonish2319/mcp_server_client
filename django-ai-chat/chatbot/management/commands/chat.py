from django.core.management.base import BaseCommand

from chatbot.services.chat import ChatService
from getpass import getpass

from chatbot.services.auth_service import LaravelAuthService
import traceback



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

        auth_service = LaravelAuthService()

        email = input("Email: ").strip()
        password = getpass("Password: ")

        try:
            auth_data = auth_service.login(email, password)
        except RuntimeError as e:
            self.stdout.write(
                self.style.ERROR(str(e))
            )
            return

        token = auth_data["token"]
        user = auth_data["user"]

        self.stdout.write(
            self.style.SUCCESS(
                f"Authenticated as {user['name']} ({user['email']})"
            )
        )

        chat = ChatService(
            mcp_url=self.MCP_URL,
            token=token,
        )

        try:

            tools = chat.initialize()

        except Exception as exc:

            self.stdout.write(
                self.style.ERROR(
                    f"Failed to connect to MCP server: {exc}"
                )
            )

            traceback.print_exc()

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
# import requests


# class OllamaService:
#     BASE_URL = "http://localhost:11434"

#     def __init__(self, model="qwen3:4b"):
#         self.model = model

#     def chat(self, message: str) -> str:
#         response = requests.post(
#             f"{self.BASE_URL}/api/chat",
#             json={
#                 "model": self.model,
#                 "messages": [
#                     {
#                         "role": "user",
#                         "content": message,
#                     }
#                 ],
#                 "stream": False,
#             },
#             timeout=120,
#         )

#         response.raise_for_status()

#         data = response.json()

#         return data["message"]["content"]


# import requests


# class OllamaService:
#     BASE_URL = "http://localhost:11434"

#     def __init__(self, model="qwen3:4b"):
#         self.model = model

#     def chat(self, messages: list[dict]) -> str:
#         response = requests.post(
#             f"{self.BASE_URL}/api/chat",
#             json={
#                 "model": self.model,
#                 "messages": messages,
#                 "stream": False,
#             },
#             timeout=120,
#         )

#         response.raise_for_status()

#         data = response.json()

#         return data["message"]["content"]

import requests


class OllamaService:
    BASE_URL = "http://localhost:11434"

    def __init__(self, model="qwen3:4b"):
        self.model = model

    def chat(
        self,
        messages: list[dict],
        tools: list[dict] | None = None,
    ) -> dict:

        payload = {
            "model": self.model,
            "messages": messages,
            "stream": False,
        }

        if tools:
            payload["tools"] = tools

        response = requests.post(
            f"{self.BASE_URL}/api/chat",
            json=payload,
            timeout=120,
        )

        response.raise_for_status()

        return response.json()
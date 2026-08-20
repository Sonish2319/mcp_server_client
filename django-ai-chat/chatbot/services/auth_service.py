import os
from django.template import response
import requests

class LaravelAuthService:
    def __init__(self):
        self.base_url = os.getenv('LARAVEL_API_URL', 'http://127.0.0.1:8000').rstrip("/")  # Default to localhost if not set

    def login(self,email: str, password: str) -> dict:

        response = requests.post(
            f"{self.base_url}/api/auth/login",
            json={
                "email":email,
                "password":password
            },
            timeout=20
        ) 

        print(response)

        if response.status_code != 200:
            try:
                data = response.json()
                message = data.get("message", "Authentication failed")
            except ValueError:
                message = "Authentication failed"

            raise RuntimeError(message)

        return response.json()


    def me(self, token: str) -> dict:
        response = requests.get(
            f"{self.base_url}/api/auth/me",
            headers={"Authorization": f"Bearer {token}"},
            timeout=20
        )
        response.raise_for_status()

        return response.json()

    def logout(self, token: str) -> None:
        response = requests.post(
            f"{self.base_url}/api/auth/logout",
            headers={
                "Authorization": f"Bearer {token}",
                "Accept": "application/json",
            },
            timeout=10,
        )

        response.raise_for_status()
class MCPToolAdapter:

    @staticmethod
    def to_ollama(tool) -> dict:
        return {
            "type": "function",
            "function": {
                "name": tool.name,
                "description": tool.description or "",
                "parameters": tool.input_schema,
            },
        }

    @classmethod
    def to_ollama_tools(
        cls,
        tools: list,
    ) -> list[dict]:

        return [
            cls.to_ollama(tool)
            for tool in tools
        ]
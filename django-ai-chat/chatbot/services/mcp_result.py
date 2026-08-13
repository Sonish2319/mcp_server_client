import json


def serialize_mcp_result(result) -> str:
    """
    Convert an MCP CallToolResult into a JSON string
    that can be passed back to the LLM.
    """

    output = {
        "is_error": bool(
            getattr(result, "isError", False)
        ),
        "content": [],
    }

    for item in result.content:

        if hasattr(item, "text"):

            output["content"].append({
                "type": "text",
                "text": item.text,
            })

        else:

            output["content"].append({
                "type": getattr(
                    item,
                    "type",
                    "unknown",
                ),
            })

    return json.dumps(
        output,
        ensure_ascii=False,
    )
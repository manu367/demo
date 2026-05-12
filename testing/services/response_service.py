def success_response(
    message="Success",
    data=None
):

    return {
        "status": True,
        "message": message,
        "data": data
    }


def error_response(
    message="Something went wrong",
    error=None
):

    return {
        "status": False,
        "message": message,
        "error": error
    }
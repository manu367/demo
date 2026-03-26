<?php

set_exception_handler(function ($e) {
    if($e instanceof  ResponseException){
        $status = $e->getStatus();
        http_response_code($status);
        $response = [
            "success" => false,
            "message" => $e->getMessage(),
        ];
        // debug ke liye banaya h mene ye
        if (true) {
            $response["file"] = $e->getFile();
            $response["line"] = $e->getLine();
            $response["trace"] = $e->getTrace();
        }
        header("Content-Type: application/json");
        echo json_encode($response, JSON_PRETTY_PRINT);
        exit;
    }
});


class ResponseException extends Exception
{
    protected $status;
    public function __construct($message, $status = 500, Exception $previous = null) {
        $this->status = $status;
        parent::__construct($message, $status, $previous);
    }
    public function getStatus() {
        return $this->status;
    }
}

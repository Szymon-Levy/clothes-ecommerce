<?php

function error_handling($type, $message, $file, $line) 
{
    throw new ErrorException($message, 0, $type, $file, $line);
}

function exceptions_handling($e)
{
    error_log($e);
    http_response_code(500);
    echo "<h1>We apologize for the inconvenience.</h1>
          <p>The site administrators have been informed of the problem. Please try again later.</p>";
}

function shutdown_handling()
{
    $error = error_get_last();
    if ($error) {
        $e = new ErrorException($error['message'], 0, $error['type'],
                                $error['file'], $error['line']);
        exceptions_handling($e);
    }
}

set_error_handler(function ($severity, $message, $file, $line) {
    if (!(error_reporting() & $severity)) {
        return false;
    }
    throw new \ErrorException($message, 0, $severity, $file, $line);
});
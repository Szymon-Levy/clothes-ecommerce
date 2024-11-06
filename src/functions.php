<?php

// Errors and exceptions
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

// Generates token
function generateToken () {
    return bin2hex(random_bytes(16));
}

// Redirects to page
function redirect (string $page) {
    $extension = $page === '' ? $page : '.php';
    header('Location: ' . DOC_ROOT . $page . $extension);
    die();
}
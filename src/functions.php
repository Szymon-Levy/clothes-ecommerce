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
function generateToken () 
{
    return bin2hex(random_bytes(16));
}

// Redirects to page
function redirect (string $page) 
{
    header('Location: ' . DOC_ROOT . $page);
    die();
}

// Creates user message in session
function createUserMessageInSession (string $content, string $type, ClothesEcommerce\Session\Session $session) 
{
    $session->setSessionVariable('user_message', ['content' => $content, 'type' => $type]);
}

// Creates admin message in session
function createAdminMessageInSession (string $content, string $type, ClothesEcommerce\Session\Session $session) 
{
    $session->setSessionVariable('admin_message', ['content' => $content, 'type' => $type]);
}

//Replaces all white space to html tags
function replaceWhitespaces (string $text)
{
    $new_text = str_replace(' ', '&nbsp;', $text);
    return nl2br($new_text);
}

// Checks if bot filled form
function isFormFilledByBot() {
    if (!isset($_POST['website']) || !$_POST['website'] == '') {
        return 'You are not allowed to send this form!';
    }
    return false;
}

// Checks if csrf token is correct
function isCsrfIncorrect(ClothesEcommerce\Session\Session $session) {
    if (!isset($_POST['csrf']) || $_POST['csrf'] != $session->csrf) {
        return 'Operation not allowed, refresh the page and try again!';
    }
    return false;
}

// Generates csrf token
function generateCSRF () 
{
    return bin2hex(random_bytes(32));
}
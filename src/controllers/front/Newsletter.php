<?php

namespace Controllers\Front;

use Controllers\BaseController;
use Core\Validation\Validation;

class Newsletter extends BaseController
{
    public function subscribe()
    {
        $this->formSecurity();

        // post data
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $policy = isset($_POST['policy']) ? trim($_POST['policy']) : null;

        // validation
        $response = [];

        $name_error = Validation::length($name, 'Name', 2, 50, true);
        $email_error = Validation::email($email, true);

        if ($name_error) {
            $response['name'] = $name_error;
        }

        if ($email_error) {
            $response['email'] = $email_error;
        }

        if (!$policy) {
            $response['policy'] = 'Accepting privacy policy is required!';
        }

        if (!empty($response)) {
            echo json_encode($response);
            exit();
        }

        // add subscriber
        $db_response = $this->models->newsletter()->addSubscriber($name, $email);

        if ($db_response == '200') {
            $response['success'] = 'We\'ve added you to our subscriber list. To confirm, please check your email and click the activation link. Link will expire after 5 minutes';
        } else if ($db_response == '1062') {
            $response['error'] = 'This e-mail address is already subscribed to our newsletter!';
        } else if ($db_response == 'email_error') {
            $response['error'] = 'A problem with sending the message to the specified email occured, check if the email address is correct and try again!';
        }

        echo json_encode($response);
        exit();
    }

    public function confirmSubscribtion()
    {
        $token = $this->router->current()->parameters()['token'] ?? '';

        if (!$token) {
            $this->router->redirect('');
        }

        $db_response = $this->models->newsletter()->confirmSubscribtion($token);

        if ($db_response == '200') {
            $this->utils->showUserMessage('Your subscription has been activated. Please check your inbox for further information.', 'success');
        } else if ($db_response == 'subscriber_not_found') {
            $this->utils->showUserMessage('Invalid token. Try again.', 'error');
        } else if ($db_response == 'already_confirmed') {
            $this->utils->showUserMessage('Your subscription has already been activated.', 'info');
        } else if ($db_response == 'token_expired') {
            $this->utils->showUserMessage('Your activation token has expired and Your subscribtion has been deleted. Join us again and hurry up with the activation!', 'error');
        } else if ($db_response == 'email_error') {
            $this->utils->showUserMessage('A problem with sending the message to the specified email occured, check if the email address is correct and try again!', 'error');
        }

        $this->router->redirect('');
    }

    public function deleteSubscribtion()
    {
        $token = $this->router->current()->parameters()['token'] ?? '';

        if (!$token) {
            $this->router->redirect('');
        }

        $db_response = $this->models->newsletter()->deleteSubscribtion($token);

        if ($db_response == '200') {
            $this->utils->showUserMessage('We’re reaching out to confirm that you have successfully unsubscribed from our newsletter. If this was a mistake or you change your mind, you can always re-subscribe.', 'success');
        } else if ($db_response == 'subscriber_not_found') {
            $this->utils->showUserMessage('Invalid token. Try again.', 'error');
        }

        $this->router->redirect('');
    }
}
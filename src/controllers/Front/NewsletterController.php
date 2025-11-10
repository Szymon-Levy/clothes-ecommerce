<?php

namespace Controllers\Front;

use Controllers\BaseController;
use Core\Validation\Validation;
use Models\NewsletterModel;

class NewsletterController extends BaseController
{
    public function __construct(
        protected NewsletterModel $newsletterModel
    ){}

    public function subscribe()
    {
        $this->formSecurity();

        // post data
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $policy = isset($_POST['policy']) ? trim($_POST['policy']) : null;

        // validation
        $response = [];

        $nameError = Validation::length($name, 'Name', 2, 50, true);
        $emailError = Validation::email($email, true);

        if ($nameError) {
            $response['name'] = $nameError;
        }

        if ($emailError) {
            $response['email'] = $emailError;
        }

        if (!$policy) {
            $response['policy'] = 'Accepting privacy policy is required!';
        }

        if (!empty($response)) {
            echo json_encode($response);
            exit();
        }

        // add subscriber
        $dbResponse = $this->newsletterModel->addSubscriber($name, $email);

        if ($dbResponse == '200') {
            $response['success'] = 'We\'ve added you to our subscriber list. To confirm, please check your email and click the activation link. Link will expire after 5 minutes';
        } else if ($dbResponse == '1062') {
            $response['error'] = 'This e-mail address is already subscribed to our newsletter!';
        } else if ($dbResponse == 'email_error') {
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

        $dbResponse = $this->newsletterModel->confirmSubscribtion($token);

        if ($dbResponse == '200') {
            $this->utils->showUserMessage('Your subscription has been activated. Please check your inbox for further information.', 'success');
        } else if ($dbResponse == 'subscriber_not_found') {
            $this->utils->showUserMessage('Invalid token. Try again.', 'error');
        } else if ($dbResponse == 'already_confirmed') {
            $this->utils->showUserMessage('Your subscription has already been activated.', 'info');
        } else if ($dbResponse == 'token_expired') {
            $this->utils->showUserMessage('Your activation token has expired and Your subscribtion has been deleted. Join us again and hurry up with the activation!', 'error');
        } else if ($dbResponse == 'email_error') {
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

        $dbResponse = $this->newsletterModel->deleteSubscribtion($token);

        if ($dbResponse == '200') {
            $this->utils->showUserMessage('We’re reaching out to confirm that you have successfully unsubscribed from our newsletter. If this was a mistake or you change your mind, you can always re-subscribe.', 'success');
        } else if ($dbResponse == 'subscriber_not_found') {
            $this->utils->showUserMessage('Invalid token. Try again.', 'error');
        }

        $this->router->redirect('');
    }
}
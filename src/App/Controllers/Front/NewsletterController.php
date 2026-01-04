<?php

namespace App\Controllers\Front;

use App\Controllers\BaseController;
use Core\Http\Response\JsonResponse;
use Core\Http\Response\RedirectResponse;
use Core\Utils\FlashMessage\FlashMessageFront;
use Core\Validation\Validation;
use App\Models\NewsletterModel;

final class NewsletterController extends BaseController
{
    public function __construct(
        protected NewsletterModel $newsletterModel
    ){}

    public function subscribe()
    {
        // post data
        $name = $this->request->post('name');
        $email = $this->request->post('email');
        $policy = $this->request->post('policy', null);

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

        if (! $policy) {
            $response['policy'] = 'Accepting privacy policy is required!';
        }

        if (! empty($response)) {
            return new JsonResponse($response);
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

        return new JsonResponse($response);
    }

    public function confirmSubscribtion(FlashMessageFront $flashMessageFront)
    {
        $token = $this->request->routeParam('token');

        $dbResponse = $this->newsletterModel->confirmSubscribtion($token);

        $message = '';
        $status = 'error';

        if ($dbResponse == '200') {
            $message = 'Your subscription has been activated. Please check your inbox for further information.';
            $status = 'success';
        } else if ($dbResponse == 'subscriber_not_found') {
            $message = 'Invalid token. Try again.';
        } else if ($dbResponse == 'already_confirmed') {
            $message = 'Your subscription has already been activated.';
            $status = 'info';
        } else if ($dbResponse == 'token_expired') {
            $message = 'Your activation token has expired and Your subscribtion has been deleted. Join us again and hurry up with the activation!';
        } else if ($dbResponse == 'email_error') {
            $message = 'A problem with sending the message to the specified email occured, check if the email address is correct and try again!';
        }

        $flashMessageFront->{$status}($message);

        return new RedirectResponse();
    }

    public function deleteSubscribtion(FlashMessageFront $flashMessageFront)
    {
        $token = $this->request->routeParam('token');

        $dbResponse = $this->newsletterModel->deleteSubscribtion($token);

        $message = '';
        $status = 'success';

        if ($dbResponse == '200') {
            $message = 'We’re reaching out to confirm that you have successfully unsubscribed from our newsletter. If this was a mistake or you change your mind, you can always re-subscribe.';
        } else if ($dbResponse == 'subscriber_not_found') {
            $message = 'Invalid token. Try again.';
            $status = 'error';
        }

        $flashMessageFront->{$status}($message);

        return new RedirectResponse();
    }
}
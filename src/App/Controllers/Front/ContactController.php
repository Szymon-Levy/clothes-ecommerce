<?php

namespace App\Controllers\Front;

use App\Controllers\BaseController;
use Core\Http\Response\HtmlResponse;
use Core\Http\Response\JsonResponse;
use Core\Validation\Validation;
use App\Models\ContactModel;
use Core\ValueObjects\Breadcrumbs;
use Core\ValueObjects\UrlSegments;

class ContactController extends BaseController
{
    public function __construct(
        protected ContactModel $contactModel
    ){}

    public function index()
    {
        $urlSegments = UrlSegments::fromUri($this->request->uri())->get();
        $breadcrumbs = Breadcrumbs::fromSegments($urlSegments)->get();

        $data = [
            'page_title' => 'Contact',
            'page_js' => 'contact',
            'url_segments' => $urlSegments,
            'breadcrumbs' => $breadcrumbs
        ];

        return new HtmlResponse(
            $this->view('front/contact.html.twig', $data)
        );
    }

    public function sendMessage()
    {
        // post data
        $name = $this->request->post('name');
        $email = $this->request->post('email');
        $subject = $this->request->post('subject');
        $message = $this->request->post('message');
        $policy = $this->request->post('policy', null);

        // validation
        $response = [];

        $nameError = Validation::length($name, 'Name', 2, 50, true);
        $emailError = Validation::email($email, true);
        $subjectAllowedValues = [
            'Shipping & Delivery',
            'Returns & Exchanges',
            'Payment Issues',
            'Technical Support',
            'Account Management',
            'Other'
        ];
        $subjectError = Validation::multiValues($subject, 'Subject', $subjectAllowedValues, true);
        $messageError = Validation::length($message, 'Message', 15, 200, true);

        if ($nameError) {
            $response['name'] = $nameError;
        }

        if ($emailError) {
            $response['email'] = $emailError;
        }

        if ($subjectError) {
            $response['subject'] = $subjectError;
        }

        if ($messageError) {
            $response['message'] = $messageError;
        }

        if (!$policy) {
            $response['policy'] = 'Accepting privacy policy is required!';
        }

        
        if (!empty($response)) {
            return new JsonResponse($response);
        }
        
        // save email data in database and send copy
        $dbResponse = $this->contactModel->sendUserMessage($name, $email, $subject, $message);
        
        if ($dbResponse == '200') {
            $response['success'] = 'Your message has been successfully sent to the administrator. We have sent a copy of your message to your email.';
        } else if ($dbResponse == 'email_error') {
            $response['error'] = 'A problem with sending the message to the specified email occured, check if the email address is correct and try again!';
        }

        return new JsonResponse($response);
    }
}
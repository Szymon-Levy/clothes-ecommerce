<?php

namespace Controllers\Front;

use Controllers\BaseController;
use Core\Validation\Validation;
use Models\ContactModel;

class ContactController extends BaseController
{
    public function __construct(
        protected ContactModel $contactModel
    ){}

    public function index()
    {
        $data = [
            'page_title' => 'Contact',
            'page_js' => 'contact'
        ];

        $this->renderView('front/contact.html.twig', $data);
    }

    public function sendMessage()
    {
        $this->formSecurity();

        // post data
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $subject = trim($_POST['subject'] ?? '');
        $message = trim($_POST['message'] ?? '');
        $policy = isset($_POST['policy']) ? trim($_POST['policy']) : null;

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
            echo json_encode($response);
            exit();
        }
        
        // save email data in database and send copy
        $dbResponse = $this->contactModel->sendUserMessage($name, $email, $subject, $message);
        
        if ($dbResponse == '200') {
            $response['success'] = 'Your message has been successfully sent to the administrator. We have sent a copy of your message to your email.';
        } else if ($dbResponse == 'email_error') {
            $response['error'] = 'A problem with sending the message to the specified email occured, check if the email address is correct and try again!';
        }

        echo json_encode($response);
        exit();
    }
}
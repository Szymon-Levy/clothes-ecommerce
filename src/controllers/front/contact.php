<?php

namespace Controllers\Front;

use Controllers\BaseController;
use Core\Validation\Validation;
use Models\Contact as ContactModel;

class Contact extends BaseController
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

        $name_error = Validation::length($name, 'Name', 2, 50, true);
        $email_error = Validation::email($email, true);
        $subject_allowed_values = [
            'Shipping & Delivery',
            'Returns & Exchanges',
            'Payment Issues',
            'Technical Support',
            'Account Management',
            'Other'
        ];
        $subject_error = Validation::multiValues($subject, 'Subject', $subject_allowed_values, true);
        $message_error = Validation::length($message, 'Message', 15, 200, true);

        if ($name_error) {
            $response['name'] = $name_error;
        }

        if ($email_error) {
            $response['email'] = $email_error;
        }

        if ($subject_error) {
            $response['subject'] = $subject_error;
        }

        if ($message_error) {
            $response['message'] = $message_error;
        }

        if (!$policy) {
            $response['policy'] = 'Accepting privacy policy is required!';
        }

        
        if (!empty($response)) {
            echo json_encode($response);
            exit();
        }
        
        // save email data in database and send copy
        $db_response = $this->contactModel->sendUserMessage($name, $email, $subject, $message);
        
        if ($db_response == '200') {
            $response['success'] = 'Your message has been successfully sent to the administrator. We have sent a copy of your message to your email.';
        } else if ($db_response == 'email_error') {
            $response['error'] = 'A problem with sending the message to the specified email occured, check if the email address is correct and try again!';
        }

        echo json_encode($response);
        exit();
    }
}
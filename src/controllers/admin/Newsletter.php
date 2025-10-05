<?php

namespace Controllers\admin;

use Controllers\BaseController;
use Core\Validation;

class Newsletter extends BaseController
{
    public function index()
    {
        $keyword = trim($_GET['keyword'] ?? '');
        $order_by = trim($_GET['orderby'] ?? '');
        $sort = trim($_GET['sort'] ?? 'a');
        $page = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT) ?: 1;

        $data = [
            'page_title' => 'Subscribers List',
            'subscribers' => $this->models->newsletter()->getSubscribersTable($keyword, $order_by, $page, $sort),
            'count' => $this->models->newsletter()->getSubscribersResultsCount(),
            'show_count_near_title' => true,
            'keyword' => $keyword,
            'page' => $page,
            'order_by' => $order_by,
            'sort' => ($sort == 'd') ? $sort : 'a',
            'page_js' => 'newsletter'
        ];

        $this->renderView('admin/newsletter/index.html.twig', $data);
    }

    public function addSubscriber()
    {
        $data = [
            'page_title' => 'Add Subscriber',
            'previous_page_path' => 'admin/newsletter',
            'page_js' => 'newsletter'
        ];

        $this->renderView('admin/newsletter/add-subscriber.html.twig', $data);
    }

    public function editSubscriber()
    {
        $id = $this->router->current()->parameters()['id'] ?? '';

        if ($id === '' || filter_var($id, FILTER_VALIDATE_INT) === false) {
            $this->utils->showAdminMessage('Wrong user id.', 'error');
            $this->router->redirect('admin/newsletter');
            exit;
        }

        $subscriber = $this->models->newsletter()->getSubscriberById($id);

        if (!$subscriber) {
            $this->utils->showAdminMessage('User with given id doesn\'t exists.', 'error');
            $this->router->redirect('admin/newsletter');
            exit;
        }

        $url_parts = $this->router->urlParts();
        array_pop($url_parts);

        $data = [
            'subscriber' => $subscriber,
            'page_title' => 'Edit Subscriber',
            'previous_page_path' => 'admin/newsletter',
            'page_js' => 'newsletter',
            'url_parts' => $url_parts
        ];

        $this->renderView('admin/newsletter/edit-subscriber.html.twig', $data);
    }

    public function addSubscriberToDB()
    {
        $this->formSecurity();

        // post data
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');

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

        if (!empty($response)) {
            echo json_encode($response);
            exit();
        }

        // add subscriber
        $db_response = $this->models->newsletter()->addSubscriber($name, $email);

        if ($db_response == '200') {
            $this->utils->showAdminMessage($name . ' has been successfully added to the subscriber list.', 'success');
            $response['success'] = true;
            $response['path'] = 'admin/newsletter';
        } else if ($db_response == '1062') {
            $response['error'] = 'This e-mail address has already been taken!';
        } else if ($db_response == 'email_error') {
            $response['error'] = 'A problem with sending the message to the specified email occured, check if the email address is correct and try again!';
        }

        echo json_encode($response);
        exit();
    }

    public function deleteSubscribers()
    {
        $this->formSecurity(['csrf']);

        // response definition
        $response = [];

        // post data
        $ids = isset($_POST['ids']) ? $_POST['ids'] :  '';
        $ids = json_decode($ids);

        if (!$ids) {
            $response['error'] = 'Incorrect data passed.';
            echo json_encode($response);
            exit();
        }

        $count = $this->models->newsletter()->deleteSubscribers($ids);

        if ($count > 0) {
            $response['count'] = $count;
            $subscriber = 'subscriber' . ($count > 1 ? 's' : '');
            $response['success'] = "$count $subscriber has been successfully removed from subscribers list.";
            echo json_encode($response);
            exit();
        } else {
            $response['info'] = "No subscriber with the given id was found.";
            echo json_encode($response);
            exit();
        }
    }

    public function editSubscriberInDB()
    {
        $this->formSecurity();

        // post data
        $id = trim($_POST['id'] ?? '');
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');

        // validation
        $response = [];

        $name_error = Validation::length($name, 'Name', 2, 50, true);
        $email_error = Validation::email($email, true);

        if (empty($id)) {
            $response['error'] = 'Id cannot be empty!';
            echo json_encode($response);
            exit();
        }

        if ($name_error) {
            $response['name'] = $name_error;
        }

        if ($email_error) {
            $response['email'] = $email_error;
        }

        if (!empty($response)) {
            echo json_encode($response);
            exit();
        }

        // edit subscriber
        $db_response = $this->models->newsletter()->editSubscriber($id, $name, $email);

        if ($db_response == '200') {
            $this->utils->showAdminMessage($name . ' has been successfully updated.', 'success');
            $response['success'] = true;
            $response['path'] = 'admin/newsletter';
        } else if ($db_response == 'subscriber_not_found') {
            $response['error'] = 'Subscriber with given id doesn\'t exist!';
        } else if ($db_response == '1062') {
            $response['error'] = 'This e-mail address has already been taken!';
        } else if ($db_response == 'email_error') {
            $response['error'] = 'A problem with sending the message to the specified email occured, check if the email address is correct and try again!';
        }

        echo json_encode($response);
        exit();
    }
}
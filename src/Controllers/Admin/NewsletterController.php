<?php

namespace Controllers\Admin;

use Controllers\BaseController;
use Core\Validation\Validation;
use Models\NewsletterModel;

class NewsletterController extends BaseController
{
    public function __construct(
        protected NewsletterModel $newsletterModel
    ){}

    public function index()
    {
        $keyword = $this->request->get('keyword', '', false);
        $orderBy = $this->request->get('orderby', '');
        $sort = $this->request->get('sort', 'a');
        $page = filter_var($this->request->get('page'), FILTER_VALIDATE_INT) ?: 1;

        $data = [
            'page_title' => 'Subscribers List',
            'subscribers' => $this->newsletterModel->getSubscribersTable($keyword, $orderBy, $page, $sort),
            'count' => $this->newsletterModel->getSubscribersResultsCount(),
            'show_count_near_title' => true,
            'keyword' => $keyword,
            'page' => $page,
            'order_by' => $orderBy,
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
        $id = $this->request->routeParam('id');

        if ($id === '' || filter_var($id, FILTER_VALIDATE_INT) === false) {
            $this->utils->showAdminMessage('Wrong user id.', 'error');
            $this->router->redirect('admin/newsletter');
            exit;
        }

        $subscriber = $this->newsletterModel->getSubscriberById($id);

        if (!$subscriber) {
            $this->utils->showAdminMessage('User with given id doesn\'t exists.', 'error');
            $this->router->redirect('admin/newsletter');
            exit;
        }

        $urlParts = $this->router->urlParts();
        array_pop($urlParts);

        $data = [
            'subscriber' => $subscriber,
            'page_title' => 'Edit Subscriber',
            'previous_page_path' => 'admin/newsletter',
            'page_js' => 'newsletter',
            'url_parts' => $urlParts
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

        $nameError = Validation::length($name, 'Name', 2, 50, true);
        $emailError = Validation::email($email, true);

        if ($nameError) {
            $response['name'] = $nameError;
        }

        if ($emailError) {
            $response['email'] = $emailError;
        }

        if (!empty($response)) {
            echo json_encode($response);
            exit();
        }

        // add subscriber
        $dbResponse = $this->newsletterModel->addSubscriber($name, $email);

        if ($dbResponse == '200') {
            $this->utils->showAdminMessage($name . ' has been successfully added to the subscriber list.', 'success');
            $response['success'] = true;
            $response['path'] = 'admin/newsletter';
        } else if ($dbResponse == '1062') {
            $response['error'] = 'This e-mail address has already been taken!';
        } else if ($dbResponse == 'email_error') {
            $response['error'] = 'A problem with sending the message to the specified email occured, check if the email address is correct and try again!';
        }

        echo json_encode($response);
        exit();
    }

    public function deleteSubscribers()
    {
        $this->formSecurity(['csrf']);

        // response
        $response = [];

        // post data
        $ids = isset($_POST['ids']) ? $_POST['ids'] :  '';
        $ids = json_decode($ids);

        if (!$ids) {
            $response['error'] = 'Incorrect data passed.';
            echo json_encode($response);
            exit();
        }

        $count = $this->newsletterModel->deleteSubscribers($ids);

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

        $nameError = Validation::length($name, 'Name', 2, 50, true);
        $emailError = Validation::email($email, true);

        if (empty($id)) {
            $response['error'] = 'Id cannot be empty!';
            echo json_encode($response);
            exit();
        }

        if ($nameError) {
            $response['name'] = $nameError;
        }

        if ($emailError) {
            $response['email'] = $emailError;
        }

        if (!empty($response)) {
            echo json_encode($response);
            exit();
        }

        // edit subscriber
        $dbResponse = $this->newsletterModel->editSubscriber($id, $name, $email);

        if ($dbResponse == '200') {
            $this->utils->showAdminMessage($name . ' has been successfully updated.', 'success');
            $response['success'] = true;
            $response['path'] = 'admin/newsletter';
        } else if ($dbResponse == 'subscriber_not_found') {
            $response['error'] = 'Subscriber with given id doesn\'t exist!';
        } else if ($dbResponse == '1062') {
            $response['error'] = 'This e-mail address has already been taken!';
        } else if ($dbResponse == 'email_error') {
            $response['error'] = 'A problem with sending the message to the specified email occured, check if the email address is correct and try again!';
        }

        echo json_encode($response);
        exit();
    }
}
<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Exports\NewsletterExport;
use Core\Http\Response\HtmlResponse;
use Core\Http\Response\JsonResponse;
use Core\Http\Response\RedirectResponse;
use Core\Utils\FlashMessage\FlashMessageAdmin;
use Core\Validation\Validation;
use App\Models\NewsletterModel;
use App\Services\exportDataTableService;
use Core\Http\Response\ResponseInterface;
use Core\Http\Response\XlsxResponse;
use Core\ValueObjects\Breadcrumbs;
use Core\ValueObjects\UrlSegments;

final class NewsletterController extends BaseController
{
    public function __construct(
        protected NewsletterModel $newsletterModel,
        protected NewsletterExport $newsletterExport,
        protected exportDataTableService $exportDataTableService
    ){}

    public function index()
    {
        $keyword = $this->request->get('keyword', '', false);
        $orderBy = $this->request->get('orderby');
        $sort = $this->request->get('sort', 'a');
        $page = filter_var(
            $this->request->get('page'), 
            FILTER_VALIDATE_INT, 
            ['options' => ['min_range' => 1]]
        ) ?: 1;

        $urlSegments = UrlSegments::fromUri($this->request->uri())->get();

        $breadcrumbs = Breadcrumbs::fromSegments($urlSegments)
            ->editAtPosition(0, ['name' => 'Dashboard'])
            ->get();

        $data = [
            'page_title' => 'Subscribers List',
            'subscribers' => $this->newsletterModel->getSubscribersTable($keyword, $orderBy, $page, $sort),
            'count' => $this->newsletterModel->getSubscribersResultsCount(),
            'show_count_near_title' => true,
            'keyword' => $keyword,
            'page' => $page,
            'order_by' => $orderBy,
            'sort' => ($sort == 'd') ? $sort : 'a',
            'page_js' => 'newsletter',
            'breadcrumbs' => $breadcrumbs
        ];

        return new HtmlResponse(
            $this->view('admin/newsletter/index.html.twig', $data)
        );
    }

    public function addSubscriber()
    {
        $urlSegments = UrlSegments::fromUri($this->request->uri())->get();

        $breadcrumbs = Breadcrumbs::fromSegments($urlSegments)
            ->editAtPosition(0, ['name' => 'Dashboard'])
            ->get();

        $data = [
            'page_title' => 'Add Subscriber',
            'previous_page_path' => 'admin/newsletter',
            'page_js' => 'newsletter',
            'breadcrumbs' => $breadcrumbs
        ];

        return new HtmlResponse(
            $this->view('admin/newsletter/add-subscriber.html.twig', $data)
        );
    }

    public function editSubscriber(FlashMessageAdmin $flashMessageAdmin)
    {
        $id = $this->request->routeParam('id');

        if ($id === '' || filter_var($id, FILTER_VALIDATE_INT) === false) {
            $flashMessageAdmin->error('Wrong user id.');

            return new RedirectResponse('admin/newsletter');
        }

        $subscriber = $this->newsletterModel->getSubscriberById($id);

        if (!$subscriber) {
            $flashMessageAdmin->error('User with given id doesn\'t exists.');

            return new RedirectResponse('admin/newsletter');
        }

        $urlSegments = UrlSegments::fromUri($this->request->uri())
            ->cutLast()
            ->get();

        $breadcrumbs = Breadcrumbs::fromSegments($urlSegments)
            ->editAtPosition(0, ['name' => 'Dashboard'])
            ->get();

        $data = [
            'subscriber' => $subscriber,
            'page_title' => 'Edit Subscriber',
            'previous_page_path' => 'admin/newsletter',
            'page_js' => 'newsletter',
            'breadcrumbs' => $breadcrumbs
        ];

        return new HtmlResponse(
            $this->view('admin/newsletter/edit-subscriber.html.twig', $data)
        );
    }

    public function addSubscriberToDB(FlashMessageAdmin $flashMessageAdmin)
    {
        // post data
        $name = $this->request->post('name');
        $email = $this->request->post('email');

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
            return new JsonResponse($response);
        }

        // add subscriber
        $dbResponse = $this->newsletterModel->addSubscriber($name, $email);

        if ($dbResponse == '200') {
            $flashMessageAdmin->success($name . ' has been successfully added to the subscriber list.');

            $response['success'] = true;
            $response['path'] = '/admin/newsletter';
        } else if ($dbResponse == '1062') {
            $response['error'] = 'This e-mail address has already been taken!';
        } else if ($dbResponse == 'email_error') {
            $response['error'] = 'A problem with sending the message to the specified email occured, check if the email address is correct and try again!';
        }

        return new JsonResponse($response);
    }

    public function deleteSubscribers()
    {
        // response
        $response = [];

        // post data
        $ids = $this->request->post('ids');
        $ids = json_decode($ids);

        if (!$ids) {
            $response['error'] = 'Incorrect data passed.';
            
            return new JsonResponse($response);
        }

        $count = $this->newsletterModel->deleteSubscribers($ids);

        if ($count > 0) {
            $response['count'] = $count;
            $subscriber = 'subscriber' . ($count > 1 ? 's' : '');
            $response['success'] = "$count $subscriber has been successfully removed from subscribers list.";
        } else {
            $response['info'] = "No subscriber with the given id was found.";
        }

        return new JsonResponse($response);
    }

    public function editSubscriberInDB(FlashMessageAdmin $flashMessageAdmin)
    {
        // post data
        $id = $this->request->post('id');
        $name = $this->request->post('name');
        $email = $this->request->post('email');

        // validation
        $response = [];

        $nameError = Validation::length($name, 'Name', 2, 50, true);
        $emailError = Validation::email($email, true);

        if (empty($id)) {
            $response['error'] = 'Id cannot be empty!';
            
            return new JsonResponse($response);
        }

        if ($nameError) {
            $response['name'] = $nameError;
        }

        if ($emailError) {
            $response['email'] = $emailError;
        }

        if (!empty($response)) {
            return new JsonResponse($response);
        }

        // edit subscriber
        $dbResponse = $this->newsletterModel->editSubscriber($id, $name, $email);

        if ($dbResponse == '200') {
            $flashMessageAdmin->success($name . ' has been successfully updated.');

            $response['success'] = true;
            $response['path'] = '/admin/newsletter';
        } else if ($dbResponse == 'subscriber_not_found') {
            $response['error'] = 'Subscriber with given id doesn\'t exist!';
        } else if ($dbResponse == '1062') {
            $response['error'] = 'This e-mail address has already been taken!';
        } else if ($dbResponse == 'email_error') {
            $response['error'] = 'A problem with sending the message to the specified email occured, check if the email address is correct and try again!';
        }

        return new JsonResponse($response);
    }

    public function exportSubscribers(): ResponseInterface
    {
        $fileName = $this->helpers->safeFilename('newsletter-subscribers');

        $exportDataDTO = $this->newsletterExport->subscribersData();

        $xlsxContent = $this->exportDataTableService->getXlsxContent($exportDataDTO, 'Subscribers');

        return new XlsxResponse($xlsxContent, $fileName);
    }
}
<?php

namespace Models;

use Models\BaseModel;
use Core\Utils\Email;

class NewsletterModel extends BaseModel
{
    protected $allowed_filter_columns = ['id', 'name', 'email', 'is_active', 'created_at'];
    protected $results_count = 0;

    public function addSubscriber(string $name, string $email)
    {
        $arguments['name'] = $name;
        $arguments['email'] = $email;

        $sql = '
            INSERT INTO newsletter_subscribers (name, email)
            VALUES (:name, :email)
        ';

        try {
            $this->database->beginTransaction();
            $this->database->SQL($sql, $arguments);

            $new_id = $this->database->lastInsertId();

            $token = $this->assignToken($new_id, 'NA');

            $email_sender = new Email($this->config->email());

            $email_data = [
                'name' => $name,
                'token' => $token
            ];

            $email_body = $this->template_engine->engine()->render('email_templates/newsletter_subscribtion_confirmation.html.twig', $email_data);

            $send_email = $email_sender->sendEmail(
                $this->config->email('admin_username'),
                $email,
                'Welcome to ' . $this->config->shop('name') . ' - Confirm Your Newsletter Subscription',
                $email_body
            );

            if ($send_email) {
                $this->database->commit();
                return '200';
            } else {
                $this->database->rollBack();
                return 'email_error';
            }
        } catch (\PDOException $e) {
            if ($e->errorInfo[1] === 1062) {
                return '1062';
            }

            throw $e;
        }
    }

    public function editSubscriber(string $id, string $name, string $email)
    {
        $existing_subscriber = $this->getSubscriberById($id);

        if (!$existing_subscriber) return 'subscriber_not_found';

        $arguments['id'] = $id;
        $arguments['name'] = $name;
        $arguments['email'] = $email;

        $sql = '
            UPDATE newsletter_subscribers 
            SET 
                name = :name,
                email = :email
            WHERE id = :id
        ';

        try {
            $this->database->beginTransaction();
            $this->database->SQL($sql, $arguments);

            if ($existing_subscriber['email'] != $email) {
                $this->desactivateSubscribtion($id);
                $this->deleteTokens($id);

                $token = $this->assignToken($id, 'NA');

                $email_sender = new Email($this->config->email());

                $email_data = [
                    'name' => $name,
                    'token' => $token
                ];

                $email_body = $this->template_engine->engine()->render('email_templates/newsletter_email_update_confirmation.html.twig', $email_data);

                $send_email = $email_sender->sendEmail(
                    $this->config->email('admin_username'),
                    $email,
                    'Confirm Your new email address in ' . $this->config->shop('name') . ' Newsletter',
                    $email_body
                );

                if (!$send_email) {
                    $this->database->rollBack();
                    return 'email_error';
                }
            }

            $this->database->commit();
            return '200';
        } catch (\PDOException $e) {
            if ($e->errorInfo[1] === 1062) {
                return '1062';
            }

            throw $e;
        }
    }

    public function deleteSubscribers(array $ids)
    {
        $in_string = str_repeat('?,', count($ids) - 1) . '?';

        $sql = "
            DELETE FROM newsletter_subscribers
            WHERE id IN ($in_string)
        ";

        $stmt = $this->database->SQL($sql, $ids);
        $deleted_count = $stmt->rowCount();
        return $deleted_count;
    }

    private function assignToken(string $subscriber_id, string $token_role_id)
    {
        $arguments['subscriber_id'] = $subscriber_id;
        $arguments['token_role_id'] = $token_role_id;

        $sql = '
            INSERT INTO newsletter_tokens (subscriber_id, token, token_role_id)
            VALUES (:subscriber_id, :token, :token_role_id)
        ';

        do {
            $token = $this->utils->generateToken();
            $arguments['token'] = $token;

            try {
                $this->database->SQL($sql, $arguments);
                return $token;
            } catch (\PDOException $e) {
                if ($e->errorInfo[1] !== 1062) {
                    throw $e;
                }
            }
        } while (true);
    }

    private function deleteTokens(string $subscriber_id)
    {
        $arguments['subscriber_id'] = $subscriber_id;

        $sql = '
            DELETE FROM newsletter_tokens 
            WHERE subscriber_id = :subscriber_id
        ';

        $this->database->SQL($sql, $arguments);
    }

    public function getSubscriberById(int|string $id)
    {
        $arguments['id'] = $id;

        $sql = '
            SELECT
                id,
                name,
                email
            FROM newsletter_subscribers
            WHERE id = :id
        ';

        return $this->database->SQL($sql, $arguments)->fetch();
    }

    private function getSubscriberByToken(string $token, string $token_role_id)
    {
        $arguments['token'] = $token;
        $arguments['token_role_id'] = $token_role_id;

        $sql = '
            SELECT
                newsletter_subscribers.id,
                newsletter_subscribers.name,
                newsletter_subscribers.email,
                newsletter_subscribers.is_active,
                UNIX_TIMESTAMP(newsletter_tokens.created_at) AS token_timestamp
            FROM newsletter_subscribers
            INNER JOIN newsletter_tokens ON newsletter_subscribers.id = newsletter_tokens.subscriber_id
            WHERE 
                newsletter_tokens.token = :token AND
                newsletter_tokens.token_role_id = :token_role_id
        ';

        return $this->database->SQL($sql, $arguments)->fetch();
    }

    private function activateSubscribtion(int|string $subscriber_id)
    {
        $sql = '
            UPDATE newsletter_subscribers 
            SET is_active = 1
            WHERE id = :subscriber_id
        ';

        $this->database->SQL($sql, ['subscriber_id' => $subscriber_id]);
    }

    private function desactivateSubscribtion(int|string $subscriber_id)
    {
        $sql = '
            UPDATE newsletter_subscribers 
            SET is_active = 0
            WHERE id = :subscriber_id
        ';

        $this->database->SQL($sql, ['subscriber_id' => $subscriber_id]);
    }

    public function confirmSubscribtion(string $token)
    {
        $subscriber = $this->getSubscriberByToken($token, 'NA');

        if (!$subscriber) {
            return 'subscriber_not_found';
        }

        if ($subscriber['is_active'] === 1) {
            return 'already_confirmed';
        }

        if ((time() - $subscriber['token_timestamp']) / 60 > 5) {
            $this->deleteSubscribers([$subscriber['id']]);
            return 'token_expired';
        }

        $subscriber_id = $subscriber['id'];

        $this->database->beginTransaction();
        $this->activateSubscribtion($subscriber_id);

        $deletion_token = $this->assignToken($subscriber_id, 'ND');

        $email_sender = new Email($this->config->email());

        $email_data = [
            'name' => $subscriber['name'],
            'token' => $deletion_token
        ];

        $email_body = $this->template_engine->engine()->render('email_templates/newsletter_welcome.html.twig', $email_data);

        $send_email = $email_sender->sendEmail(
            $this->config->email('admin_username'),
            $subscriber['email'],
            'Your newsletter subscribtion at ' . $this->config->shop('name') . ' is active.',
            $email_body
        );

        if (!$send_email) {
            $this->database->rollBack();
            return 'email_error';
        }

        $this->database->commit();
        return '200';
    }

    public function deleteSubscribtion(string $token)
    {
        $subscriber = $this->getSubscriberByToken($token, 'ND');

        if (!$subscriber) {
            return 'subscriber_not_found';
        }

        $this->deleteSubscribers([$subscriber['id']]);

        return '200';
    }

    public function getSubscribersResultsCount()
    {
        return $this->results_count;
    }

    public function getSubscribersTable(string $keyword, string $order_by, int $page, string $sort)
    {
        $arguments = [];
        $where_clauses = [];
        $from = 'FROM newsletter_subscribers';

        if ($keyword) {
            $arguments['keyword'] = '%' . $keyword . '%';
            $where_clauses[] = 'email LIKE :keyword';
        }

        $where = $where_clauses ? ' WHERE ' . implode(' AND ', $where_clauses) : '';

        // Get count of results to pagination
        $sql = "
            SELECT COUNT(id) AS count $from $where;
        ";

        $this->results_count = $this->database->SQL($sql, $arguments)->fetch()['count'];

        $order = ' ORDER BY ';
        $order .= ($order_by && in_array($order_by, $this->allowed_filter_columns)) ? "$order_by " : 'id ';
        $order .= ($order_by && in_array($order_by, ['name', 'email'])) ? ' COLLATE utf8mb4_polish_ci ' : '';
        $order .= ($sort == 'd') ? 'DESC' : 'ASC';

        $admin_pagination = $this->config->system('admin_pagination');
        $limit_clause = sprintf(' LIMIT %d OFFSET %d', $admin_pagination, ($page - 1) * $admin_pagination);

        $sql = "
            SELECT 
                id,
                name,
                email,
                is_active,
                created_at
            $from 
            $where 
            $order 
            $limit_clause;
        ";

        return $this->database->SQL($sql, $arguments)->fetchAll();
    }

    public function getSubscribersDataToExport()
    {
        $data['file_name'] = 'newsletter-subscribers';
        $data['headings'] = ['ID', 'SUBSCRIBER NAME', 'EMAIL', 'CREATED DATE', 'ACTIVITY STATUS'];
        $data['db_columns'] = ['id', 'name', 'email', 'created_at', 'activity_status'];

        $sql = '
            SELECT
                id,
                name,
                email,
                created_at,
                is_active,
                CASE
                    WHEN is_active = "1" THEN "Active"
                    ELSE "Inactive"
                END AS activity_status
            FROM newsletter_subscribers
            ORDER BY id;
        ';
        $data['db_data'] = $this->database->SQL($sql)->fetchAll();
        return $data;
    }
}
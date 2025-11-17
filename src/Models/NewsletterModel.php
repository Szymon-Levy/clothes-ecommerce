<?php

namespace Models;

use Models\BaseModel;
use Core\Utils\Email;

class NewsletterModel extends BaseModel
{
    protected $allowedFilterColumns = ['id', 'name', 'email', 'is_active', 'created_at'];
    protected $resultsCount = 0;

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

            $newId = $this->database->lastInsertId();

            $token = $this->assignToken($newId, 'NA');

            $emailSender = new Email($this->config->email());

            $emailData = [
                'name' => $name,
                'token' => $token
            ];

            $emailBody = $this->templateEngine->render('email_templates/newsletter_subscribtion_confirmation.html.twig', $emailData);

            $sendEmail = $emailSender->sendEmail(
                $this->config->email('admin_username'),
                $email,
                'Welcome to ' . $this->config->shop('name') . ' - Confirm Your Newsletter Subscription',
                $emailBody
            );

            if ($sendEmail) {
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
        $existingSubscriber = $this->getSubscriberById($id);

        if (!$existingSubscriber) return 'subscriber_not_found';

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

            if ($existingSubscriber['email'] != $email) {
                $this->desactivateSubscribtion($id);
                $this->deleteTokens($id);

                $token = $this->assignToken($id, 'NA');

                $emailSender = new Email($this->config->email());

                $emailData = [
                    'name' => $name,
                    'token' => $token
                ];

                $emailBody = $this->templateEngine->render('email_templates/newsletter_email_update_confirmation.html.twig', $emailData);

                $sendEmail = $emailSender->sendEmail(
                    $this->config->email('admin_username'),
                    $email,
                    'Confirm Your new email address in ' . $this->config->shop('name') . ' Newsletter',
                    $emailBody
                );

                if (!$sendEmail) {
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
        $inString = str_repeat('?,', count($ids) - 1) . '?';

        $sql = "
            DELETE FROM newsletter_subscribers
            WHERE id IN ($inString)
        ";

        $stmt = $this->database->SQL($sql, $ids);
        $deletedCount = $stmt->rowCount();
        return $deletedCount;
    }

    protected function assignToken(string $subscriberId, string $tokenRoleId)
    {
        $arguments['subscriber_id'] = $subscriberId;
        $arguments['token_role_id'] = $tokenRoleId;

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

    protected function deleteTokens(string $subscriberId)
    {
        $arguments['subscriber_id'] = $subscriberId;

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

    protected function getSubscriberByToken(string $token, string $tokenRoleId)
    {
        $arguments['token'] = $token;
        $arguments['token_role_id'] = $tokenRoleId;

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

    protected function activateSubscribtion(int|string $subscriberId)
    {
        $sql = '
            UPDATE newsletter_subscribers 
            SET is_active = 1
            WHERE id = :subscriber_id
        ';

        $this->database->SQL($sql, ['subscriber_id' => $subscriberId]);
    }

    protected function desactivateSubscribtion(int|string $subscriberId)
    {
        $sql = '
            UPDATE newsletter_subscribers 
            SET is_active = 0
            WHERE id = :subscriber_id
        ';

        $this->database->SQL($sql, ['subscriber_id' => $subscriberId]);
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

        $subscriberId = $subscriber['id'];

        $this->database->beginTransaction();
        $this->activateSubscribtion($subscriberId);

        $deletionToken = $this->assignToken($subscriberId, 'ND');

        $emailSender = new Email($this->config->email());

        $emailData = [
            'name' => $subscriber['name'],
            'token' => $deletionToken
        ];

        $emailBody = $this->templateEngine->render('email_templates/newsletter_welcome.html.twig', $emailData);

        $sendEmail = $emailSender->sendEmail(
            $this->config->email('admin_username'),
            $subscriber['email'],
            'Your newsletter subscribtion at ' . $this->config->shop('name') . ' is active.',
            $emailBody
        );

        if (!$sendEmail) {
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
        return $this->resultsCount;
    }

    public function getSubscribersTable(string $keyword, string $orderBy, int $page, string $sort)
    {
        $arguments = [];
        $whereClauses = [];
        $from = 'FROM newsletter_subscribers';

        if ($keyword) {
            $arguments['keyword'] = '%' . $keyword . '%';
            $whereClauses[] = 'email LIKE :keyword';
        }

        $where = $whereClauses ? ' WHERE ' . implode(' AND ', $whereClauses) : '';

        // Get count of results to pagination
        $sql = "
            SELECT COUNT(id) AS count $from $where;
        ";

        $this->resultsCount = $this->database->SQL($sql, $arguments)->fetch()['count'];

        $order = ' ORDER BY ';
        $order .= ($orderBy && in_array($orderBy, $this->allowedFilterColumns)) ? "$orderBy " : 'id ';
        $order .= ($orderBy && in_array($orderBy, ['name', 'email'])) ? ' COLLATE utf8mb4_polish_ci ' : '';
        $order .= ($sort == 'd') ? 'DESC' : 'ASC';

        $adminPagination = $this->config->system('admin_pagination');
        $limitClause = sprintf(' LIMIT %d OFFSET %d', $adminPagination, ($page - 1) * $adminPagination);

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
            $limitClause;
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
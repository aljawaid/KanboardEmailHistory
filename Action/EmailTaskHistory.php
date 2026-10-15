<?php

namespace Kanboard\Plugin\KanboardEmailHistory\Action;

use Kanboard\Action\Base;
use Kanboard\Core\Security\Role;
use Kanboard\Model\TaskModel;
use Kanboard\Model\UserMetadataModel;

/**
 * Queues a privacy-filtered task history email when a task is closed.
 *
 * Related test file: tests/EmailTaskHistoryTest.php.
 */
class EmailTaskHistory extends Base
{
    /**
     * Describe the automatic action.
     *
     * @return string
     */
    public function getDescription()
    {
        return t('EmailTaskHistory > Send task description and permitted comment history on task closure');
    }

    /**
     * Return compatible events.
     *
     * @return array
     */
    public function getCompatibleEvents()
    {
        return array(
            TaskModel::EVENT_CLOSE,
        );
    }

    /**
     * Return the action configuration fields.
     *
     * @return array
     */
    public function getActionRequiredParameters()
    {
        return array(
            'email_subject' => t('Email Subject'),
            'send_to' => array(
                'assignee' => t('Send to Assignee'),
                'creator' => t('Send to Creator'),
                'both' => t('Assignee & Creator'),
                'assignee_project_email' => t('Assignee & Project Email'),
                'creator_project_email' => t('Creator & Project Email'),
                'project_email' => t('Project Email'),
                'all' => t('Assignee, Creator & Project Email'),
            ),
            'check_box_include_task_title' => t('Include the Task Title and ID in the subject line?'),
            'check_box_include_project_name' => t('Include the Project Name in the subject line?'),
            'check_box_include_identifier' => t('Include the Project Identifier in the subject line?'),
        );
    }

    /**
     * Return fields required from the task-close event.
     *
     * @return array
     */
    public function getEventRequiredParameters()
    {
        return array(
            'task' => array(
                'id',
                'project_id',
                'owner_id',
                'creator_id',
                'title',
            ),
        );
    }

    /**
     * Queue one email per unique address and record each accepted queue request.
     *
     * Project mailboxes intentionally receive only standard-user comments because
     * they do not represent an authenticated Kanboard identity.
     *
     * @param  array $data Event data dictionary
     * @return bool
     */
    public function doAction(array $data)
    {
        $task = $data['task'];
        $project = $this->projectModel->getById($task['project_id']);
        $sorting = $this->userMetadataCacheDecorator->get(UserMetadataModel::KEY_COMMENT_SORTING_DIRECTION, 'ASC');
        $comments = $this->commentModel->getAll($task['id'], $sorting);
        $subject = $this->buildSubject($task, $project);
        $recipients = $this->buildRecipients($task, $project);
        $auditUserId = $this->getAuditUserId($task);
        $historyQueued = false;

        foreach ($recipients as $recipient) {
            $permittedComments = $this->filterCommentsForRole($comments, $recipient['role']);
            $html = $this->renderMessage($task, $project, $permittedComments);

            $this->emailClient->send(
                $recipient['email'],
                $recipient['name'],
                $subject,
                $html
            );

            if ($auditUserId > 0) {
                $this->commentModel->create(array(
                    'comment' => t(
                        'TASK CLOSED: The task history email was queued for %s (%s) [Subject: %s]',
                        $recipient['audit_name'],
                        $this->translateRecipientLabels($recipient['labels']),
                        $subject
                    ),
                    'user_id' => $auditUserId,
                    'task_id' => $task['id'],
                    'visibility' => Role::APP_USER,
                ));
            }

            $historyQueued = true;
        }

        return $historyQueued;
    }

    /**
     * This action has no condition beyond the task-close event.
     *
     * @param  array $data Event data dictionary
     * @return bool
     */
    public function hasRequiredCondition(array $data)
    {
        return true;
    }

    /**
     * Build a stable subject from the configured options.
     *
     * @param  array $task
     * @param  array $project
     * @return string
     */
    private function buildSubject(array $task, array $project)
    {
        $subject = $this->sanitizeSubjectPart($this->getParam('email_subject'));
        $parts = array();

        if ($subject === '') {
            $subject = t('Activity Report');
        }

        if ($this->isEnabled('check_box_include_project_name')) {
            $parts[] = $this->sanitizeSubjectPart(isset($project['name']) ? $project['name'] : '');
        }

        if ($this->isEnabled('check_box_include_identifier')) {
            $parts[] = $this->sanitizeSubjectPart(isset($project['identifier']) ? $project['identifier'] : '');
        }

        if ($this->isEnabled('check_box_include_task_title')) {
            $parts[] = trim($this->sanitizeSubjectPart($task['title']) . ' (#' . (int) $task['id'] . ')');
        }

        $parts = array_values(array_filter($parts, function ($part) {
            return $part !== '';
        }));

        return empty($parts) ? $subject : $subject . ': ' . implode(' ', $parts);
    }

    /**
     * Remove line breaks and control characters from an email subject part.
     *
     * @param  mixed $value
     * @return string
     */
    private function sanitizeSubjectPart($value)
    {
        $value = preg_replace('/[\r\n\t]+/', ' ', trim((string) $value));
        return preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/', '', $value);
    }

    /**
     * Determine whether a checkbox parameter is enabled.
     *
     * @param  string $name
     * @return bool
     */
    private function isEnabled($name)
    {
        $value = $this->getParam($name);
        return $value === true || $value === 1 || $value === '1' || $value === 'on';
    }

    /**
     * Build and de-duplicate the selected recipients.
     *
     * The least-privileged role wins when an address is shared by multiple
     * recipients so restricted comments cannot escape through a shared mailbox.
     *
     * @param  array $task
     * @param  array $project
     * @return array
     */
    private function buildRecipients(array $task, array $project)
    {
        $sendTo = $this->getParam('send_to', 'all');
        $allowedOptions = array(
            'assignee',
            'creator',
            'both',
            'assignee_project_email',
            'creator_project_email',
            'project_email',
            'all',
        );

        if (!in_array($sendTo, $allowedOptions, true)) {
            $sendTo = 'all';
        }

        $recipients = array();

        if (in_array($sendTo, array('assignee', 'both', 'assignee_project_email', 'all'), true)) {
            $this->addUserRecipient($recipients, $task['owner_id'], 'Task assignee');
        }

        if (in_array($sendTo, array('creator', 'both', 'creator_project_email', 'all'), true)) {
            $this->addUserRecipient($recipients, $task['creator_id'], 'Task creator');
        }

        if (in_array($sendTo, array('project_email', 'assignee_project_email', 'creator_project_email', 'all'), true)) {
            $this->addRecipient(
                $recipients,
                isset($project['email']) ? $project['email'] : '',
                !empty($project['name']) ? $project['name'] : t('Project email'),
                t('project email address'),
                Role::APP_USER,
                'Project Email'
            );
        }

        return array_values($recipients);
    }

    /**
     * Add a Kanboard user to the recipient collection.
     *
     * @param  array   $recipients
     * @param  integer $userId
     * @param  string  $label
     * @return void
     */
    private function addUserRecipient(array &$recipients, $userId, $label)
    {
        if ((int) $userId <= 0) {
            return;
        }

        $user = $this->userModel->getById($userId);

        if (empty($user['email'])) {
            return;
        }

        $username = !empty($user['username']) ? $user['username'] : t('Unknown user');
        $this->addRecipient(
            $recipients,
            $user['email'],
            !empty($user['name']) ? $user['name'] : $username,
            '@' . $username,
            isset($user['role']) ? $user['role'] : Role::APP_USER,
            $label
        );
    }

    /**
     * Add or merge a recipient by normalised email address.
     *
     * @param  array  $recipients
     * @param  string $email
     * @param  string $name
     * @param  string $auditName
     * @param  string $role
     * @param  string $label
     * @return void
     */
    private function addRecipient(array &$recipients, $email, $name, $auditName, $role, $label)
    {
        $email = trim((string) $email);

        if ($email === '') {
            return;
        }

        $key = strtolower($email);
        $role = $this->normaliseRole($role);

        if (!isset($recipients[$key])) {
            $recipients[$key] = array(
                'email' => $email,
                'name' => $name,
                'audit_name' => $auditName,
                'role' => $role,
                'labels' => array($label),
            );
            return;
        }

        if (!in_array($label, $recipients[$key]['labels'], true)) {
            $recipients[$key]['labels'][] = $label;
        }

        if ($this->roleRank($role) < $this->roleRank($recipients[$key]['role'])) {
            $recipients[$key]['role'] = $role;
        }
    }

    /**
     * Filter comments to the visibility available to a recipient role.
     *
     * @param  array  $comments
     * @param  string $role
     * @return array
     */
    private function filterCommentsForRole(array $comments, $role)
    {
        $maximumRank = $this->roleRank($role);

        return array_values(array_filter($comments, function (array $comment) use ($maximumRank) {
            $visibility = isset($comment['visibility']) ? $comment['visibility'] : Role::APP_USER;
            return $this->isKnownRole($visibility) && $this->roleRank($visibility) <= $maximumRank;
        }));
    }

    /**
     * Render the complete email body for one recipient.
     *
     * @param  array $task
     * @param  array $project
     * @param  array $comments
     * @return string
     */
    private function renderMessage(array $task, array $project, array $comments)
    {
        $html = $this->template->render('kanboardEmailHistory:notification/task_create', array('task' => $task));
        $html .= $this->template->render('kanboardEmailHistory:task_comments/show', array(
            'task' => $task,
            'comments' => $comments,
            'project' => $project,
            'editable' => false,
        ));
        $html .= $this->template->render('kanboardEmailHistory:notification/footer', array('task' => $task));

        return $html;
    }

    /**
     * Translate and combine audit labels after recipient de-duplication.
     *
     * @param  array $labels
     * @return string
     */
    private function translateRecipientLabels(array $labels)
    {
        $translated = array();

        foreach ($labels as $label) {
            $translated[] = t($label);
        }

        return implode(', ', $translated);
    }

    /**
     * Select the best available author for the automatic audit comment.
     *
     * Background jobs do not carry the user who closed the task, so the task
     * creator is the stable fallback when no authenticated session is present.
     *
     * @param  array $task
     * @return integer
     */
    private function getAuditUserId(array $task)
    {
        $sessionUserId = (int) $this->userSession->getId();

        if ($sessionUserId > 0) {
            return $sessionUserId;
        }

        if (!empty($task['creator_id'])) {
            return (int) $task['creator_id'];
        }

        return !empty($task['owner_id']) ? (int) $task['owner_id'] : 0;
    }

    /**
     * Constrain unknown roles to the least-privileged visibility.
     *
     * @param  string $role
     * @return string
     */
    private function normaliseRole($role)
    {
        return $this->isKnownRole($role) ? $role : Role::APP_USER;
    }

    /**
     * Check whether a role is a supported comment visibility.
     *
     * @param  string $role
     * @return bool
     */
    private function isKnownRole($role)
    {
        return in_array($role, array(Role::APP_USER, Role::APP_MANAGER, Role::APP_ADMIN), true);
    }

    /**
     * Convert a role to its visibility rank.
     *
     * @param  string $role
     * @return integer
     */
    private function roleRank($role)
    {
        $ranks = array(
            Role::APP_USER => 1,
            Role::APP_MANAGER => 2,
            Role::APP_ADMIN => 3,
        );

        return isset($ranks[$role]) ? $ranks[$role] : 1;
    }
}

<?php

/**
 * Verifies recipient privacy, de-duplication, subjects, audit comments, metadata, and translations.
 *
 * Related production files: Action/EmailTaskHistory.php, Plugin.php, and the Locale translation dictionaries.
 */

namespace Kanboard\Plugin\KanboardEmailHistory\Tests;

use Kanboard\Core\Security\Role;
use Kanboard\Plugin\KanboardEmailHistory\Action\EmailTaskHistory;
use Pimple\Container;

// phpcs:disable PSR1.Classes.ClassDeclaration.MultipleClasses, PSR1.Files.SideEffects.FoundWithSymbols -- Focused test doubles are kept with their sole consumer.

final class KanboardEmailHistoryFakeEmailClient
{
    public $messages = array();

    public function send($email, $name, $subject, $html)
    {
        $this->messages[] = array(
            'email' => $email,
            'name' => $name,
            'subject' => $subject,
            'html' => $html,
        );

        return $this;
    }
}

final class KanboardEmailHistoryFakeCommentModel
{
    public $comments;


    public $created = array();

    public function __construct(array $comments)
    {
        $this->comments = $comments;
    }

    public function getAll($taskId, $sorting)
    {
        return $this->comments;
    }

    public function create(array $values)
    {
        $this->created[] = $values;
        return count($this->created);
    }
}

final class KanboardEmailHistoryFakeProjectModel
{
    private $project;

    public function __construct(array $project)
    {
        $this->project = $project;
    }

    public function getById($projectId)
    {
        return $this->project;
    }
}

final class KanboardEmailHistoryFakeUserModel
{
    private $users;

    public function __construct(array $users)
    {
        $this->users = $users;
    }

    public function getById($userId)
    {
        return isset($this->users[$userId]) ? $this->users[$userId] : array();
    }
}

final class KanboardEmailHistoryFakeMetadata
{
    public function get($key, $default)
    {
        return $default;
    }
}

final class KanboardEmailHistoryFakeUserSession
{
    private $userId;

    public function __construct($userId)
    {
        $this->userId = $userId;
    }

    public function getId()
    {
        return $this->userId;
    }
}

final class KanboardEmailHistoryFakeTemplate
{
    public function render($template, array $variables)
    {
        if ($template === 'kanboardEmailHistory:task_comments/show') {
            return 'COMMENTS:' . implode(',', array_column($variables['comments'], 'id')) . ';';
        }

        return '[' . $template . ']';
    }
}

function kanboardEmailHistoryTask()
{
    return array(
        'id' => 42,
        'project_id' => 8,
        'owner_id' => 1,
        'creator_id' => 2,
        'title' => 'Task title',
    );
}

function kanboardEmailHistoryProject($email = 'project@example.test')
{
    return array(
        'id' => 8,
        'name' => 'Project Alpha',
        'identifier' => 'PA',
        'email' => $email,
    );
}

function kanboardEmailHistoryUsers($assigneeEmail = 'admin@example.test', $creatorEmail = 'manager@example.test')
{
    return array(
        1 => array(
            'id' => 1,
            'username' => 'admin-user',
            'name' => 'Admin User',
            'email' => $assigneeEmail,
            'role' => Role::APP_ADMIN,
        ),
        2 => array(
            'id' => 2,
            'username' => 'manager-user',
            'name' => 'Manager User',
            'email' => $creatorEmail,
            'role' => Role::APP_MANAGER,
        ),
    );
}

function kanboardEmailHistoryComments()
{
    return array(
        array('id' => 1, 'visibility' => Role::APP_USER),
        array('id' => 2, 'visibility' => Role::APP_MANAGER),
        array('id' => 3, 'visibility' => Role::APP_ADMIN),
        array('id' => 4, 'visibility' => 'unknown-role'),
    );
}

function kanboardEmailHistoryBuildAction(array $users, array $project, array $comments, $sessionUserId = 0)
{
    $testContainer = new Container();
    $emailClient = new KanboardEmailHistoryFakeEmailClient();
    $commentModel = new KanboardEmailHistoryFakeCommentModel($comments);

    $testContainer['emailClient'] = $emailClient;
    $testContainer['commentModel'] = $commentModel;
    $testContainer['projectModel'] = new KanboardEmailHistoryFakeProjectModel($project);
    $testContainer['userModel'] = new KanboardEmailHistoryFakeUserModel($users);
    $testContainer['userMetadataCacheDecorator'] = new KanboardEmailHistoryFakeMetadata();
    $testContainer['userSession'] = new KanboardEmailHistoryFakeUserSession($sessionUserId);
    $testContainer['template'] = new KanboardEmailHistoryFakeTemplate();

    return array(new EmailTaskHistory($testContainer), $emailClient, $commentModel);
}

function kanboardEmailHistoryConfigureAction(EmailTaskHistory $action, array $params)
{
    foreach ($params as $name => $value) {
        $action->setParam($name, $value);
    }
}

function kanboardEmailHistoryMessageByAddress(array $messages, $email)
{
    foreach ($messages as $message) {
        if (strcasecmp($message['email'], $email) === 0) {
            return $message;
        }
    }

    throw new RuntimeException('Expected message for ' . $email . '.');
}

$plugin = $loadedPlugins[$pluginName];
kanboardEmailHistoryAssertSame('2.9.0', $plugin->getPluginVersion(), 'The plugin version must match the uninstall-data feature release.');
kanboardEmailHistoryAssertSame('>=1.2.54', $plugin->getCompatibleVersion(), 'Compatibility must not advertise an untested Kanboard release.');

$subjectCases = array(
    array(array(), 'Quarterly report'),
    array(array('check_box_include_task_title' => '1'), 'Quarterly report: Task title (#42)'),
    array(array('check_box_include_project_name' => '1'), 'Quarterly report: Project Alpha'),
    array(array('check_box_include_identifier' => '1'), 'Quarterly report: PA'),
    array(array('check_box_include_task_title' => '1', 'check_box_include_identifier' => '1'), 'Quarterly report: PA Task title (#42)'),
    array(array('check_box_include_project_name' => '1', 'check_box_include_identifier' => '1'), 'Quarterly report: Project Alpha PA'),
    array(array('check_box_include_project_name' => '1', 'check_box_include_task_title' => '1'), 'Quarterly report: Project Alpha Task title (#42)'),
    array(array('check_box_include_project_name' => '1', 'check_box_include_identifier' => '1', 'check_box_include_task_title' => '1'), 'Quarterly report: Project Alpha PA Task title (#42)'),
);

foreach ($subjectCases as $subjectCase) {
    list($action, $emailClient) = kanboardEmailHistoryBuildAction(kanboardEmailHistoryUsers(), kanboardEmailHistoryProject(), array());
    kanboardEmailHistoryConfigureAction($action, array_merge(array(
        'email_subject' => 'Quarterly report',
        'send_to' => 'project_email',
    ), $subjectCase[0]));
    kanboardEmailHistoryAssertTrue($action->doAction(array('task' => kanboardEmailHistoryTask())), 'A configured project mailbox must accept a queue request.');
    kanboardEmailHistoryAssertSame($subjectCase[1], $emailClient->messages[0]['subject'], 'Subject options must compose deterministically.');
}

list($action, $emailClient) = kanboardEmailHistoryBuildAction(kanboardEmailHistoryUsers(), kanboardEmailHistoryProject(), array());
kanboardEmailHistoryConfigureAction($action, array(
    'email_subject' => "  Quarterly report\r\nBcc: unexpected@example.test  ",
    'send_to' => 'project_email',
));
$action->doAction(array('task' => kanboardEmailHistoryTask()));
kanboardEmailHistoryAssertSame('Quarterly report Bcc: unexpected@example.test', $emailClient->messages[0]['subject'], 'Subject line breaks must be removed without discarding custom text.');

list($action, $emailClient) = kanboardEmailHistoryBuildAction(kanboardEmailHistoryUsers(), kanboardEmailHistoryProject(), array());
kanboardEmailHistoryConfigureAction($action, array(
    'email_subject' => " \t ",
    'send_to' => 'project_email',
));
$action->doAction(array('task' => kanboardEmailHistoryTask()));
kanboardEmailHistoryAssertSame('Activity Report', $emailClient->messages[0]['subject'], 'A whitespace-only subject must use the translated fallback.');

list($action, $emailClient, $commentModel) = kanboardEmailHistoryBuildAction(
    kanboardEmailHistoryUsers(),
    kanboardEmailHistoryProject(),
    kanboardEmailHistoryComments()
);
kanboardEmailHistoryConfigureAction($action, array('email_subject' => 'History', 'send_to' => 'all'));
kanboardEmailHistoryAssertTrue($action->doAction(array('task' => kanboardEmailHistoryTask())), 'At least one accepted recipient must report success.');
kanboardEmailHistoryAssertSame(3, count($emailClient->messages), 'Distinct assignee, creator, and project addresses must each receive one message.');
kanboardEmailHistoryAssertContains('COMMENTS:1,2,3;', kanboardEmailHistoryMessageByAddress($emailClient->messages, 'admin@example.test')['html'], 'Administrators may receive user, manager, and administrator comments.');
kanboardEmailHistoryAssertContains('COMMENTS:1,2;', kanboardEmailHistoryMessageByAddress($emailClient->messages, 'manager@example.test')['html'], 'Managers may receive user and manager comments only.');
kanboardEmailHistoryAssertContains('COMMENTS:1;', kanboardEmailHistoryMessageByAddress($emailClient->messages, 'project@example.test')['html'], 'Project mailboxes must receive standard-user comments only.');
kanboardEmailHistoryAssertSame('Project Alpha', kanboardEmailHistoryMessageByAddress($emailClient->messages, 'project@example.test')['name'], 'Project mail must use the project name instead of the task creator name.');
kanboardEmailHistoryAssertSame(3, count($commentModel->created), 'Each unique queued address must create one audit comment.');

foreach ($commentModel->created as $auditComment) {
    kanboardEmailHistoryAssertSame(2, $auditComment['user_id'], 'Background audit comments must use the task creator as the stable fallback author.');
    kanboardEmailHistoryAssertSame(Role::APP_USER, $auditComment['visibility'], 'Audit comments must use standard visibility.');
    kanboardEmailHistoryAssertContains('was queued', $auditComment['comment'], 'Audit comments must describe queue acceptance truthfully.');
    kanboardEmailHistoryAssertNotContains('has been emailed', $auditComment['comment'], 'Audit comments must not claim final delivery.');
}

$sharedUsers = kanboardEmailHistoryUsers('Shared@example.test', 'shared@example.test');
$sharedUsers[2]['role'] = Role::APP_USER;
list($action, $emailClient, $commentModel) = kanboardEmailHistoryBuildAction(
    $sharedUsers,
    kanboardEmailHistoryProject('SHARED@example.test'),
    kanboardEmailHistoryComments()
);
kanboardEmailHistoryConfigureAction($action, array('email_subject' => 'History', 'send_to' => 'all'));
$action->doAction(array('task' => kanboardEmailHistoryTask()));
kanboardEmailHistoryAssertSame(1, count($emailClient->messages), 'A shared address must be de-duplicated case-insensitively.');
kanboardEmailHistoryAssertContains('COMMENTS:1;', $emailClient->messages[0]['html'], 'A shared address must receive the least-privileged comment set.');
kanboardEmailHistoryAssertSame(1, count($commentModel->created), 'A shared address must create one audit comment.');
kanboardEmailHistoryAssertContains('Task assignee, Task creator, Project Email', $commentModel->created[0]['comment'], 'The audit comment must retain every reason for the de-duplicated recipient.');

list($action, $emailClient, $commentModel) = kanboardEmailHistoryBuildAction(array(), kanboardEmailHistoryProject(''), array());
kanboardEmailHistoryConfigureAction($action, array('email_subject' => 'History', 'send_to' => 'all'));
kanboardEmailHistoryAssertFalse($action->doAction(array('task' => kanboardEmailHistoryTask())), 'Missing recipient addresses must not report a queued history.');
kanboardEmailHistoryAssertSame(0, count($emailClient->messages), 'Missing recipient addresses must not queue messages.');
kanboardEmailHistoryAssertSame(0, count($commentModel->created), 'Missing recipient addresses must not create audit comments.');

$english = require $pluginRoot . '/Locale/en_GB/translations.php';
$german = require $pluginRoot . '/Locale/de_DE/translations.php';
$french = require $pluginRoot . '/Locale/fr_FR/translations.php';
$starter = require $pluginRoot . '/Locale/translations-starter-template.php';

kanboardEmailHistoryAssertSame(array_keys($english), array_keys($german), 'German translations must cover the complete English key set in the same order.');
kanboardEmailHistoryAssertSame(array_keys($english), array_keys($french), 'French translations must cover the complete English key set in the same order.');
kanboardEmailHistoryAssertSame(array_keys($english), array_keys($starter), 'The starter template must cover the complete production key set in the same order.');
kanboardEmailHistoryAssertSame('Fälligkeitsdatum:', $german['Due Date:'], 'The German due-date translation must describe a deadline, not a birth date.');
kanboardEmailHistoryAssertSame('ID: ', $german['ID: '], 'The German ID label must not be mistranslated.');
kanboardEmailHistoryAssertSame('Colonne du tableau :', $french['Board Column:'], 'The French board-column translation must use the Kanban meaning of board.');
kanboardEmailHistoryAssertSame('Position de la tâche :', $french['Task Position:'], 'The French task-position translation must describe list position.');

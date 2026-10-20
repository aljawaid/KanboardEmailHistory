<?php

/**
 * Verifies scoped retention, restoration, deletion, schema, settings, and administrator UI safeguards.
 *
 * Related production files: Model/UninstallDataModel.php,
 * Controller/EmailHistorySettingsController.php, Schema/*.php, and Template/config/*.php.
 */

namespace Kanboard\Plugin\KanboardEmailHistory\Tests;

use Kanboard\Model\TaskModel;
use Kanboard\Plugin\KanboardEmailHistory\Model\UninstallDataModel;
use Pimple\Container;
use Psr\Log\AbstractLogger;
use Symfony\Component\EventDispatcher\EventDispatcher;

// phpcs:disable PSR1.Classes.ClassDeclaration.MultipleClasses, PSR1.Files.SideEffects.FoundWithSymbols -- Focused test doubles are kept with their sole consumer.

final class KanboardEmailHistoryUninstallProbeLogger extends AbstractLogger
{
    public $records = array();

    public function log($level, $message, array $context = array())
    {
        $this->records[] = array('level' => $level, 'message' => $message, 'context' => $context);
    }
}

final class KanboardEmailHistoryUninstallProbeSession
{
    public function isLogged()
    {
        return false;
    }
}

$uninstallDataModel = $container['uninstallDataModel'];
$projectId = $container['projectModel']->create(array('name' => 'Email History uninstall policy test'));

kanboardEmailHistoryAssertTrue($projectId > 0, 'The uninstall-data test project must be created.');
kanboardEmailHistoryAssertSame(
    UninstallDataModel::POLICY_RETAIN,
    $uninstallDataModel->getPolicy(),
    'Existing and clean installations must default to retaining action configuration.'
);
kanboardEmailHistoryAssertTrue(
    $uninstallDataModel->savePolicy(UninstallDataModel::POLICY_DELETE),
    'Administrators must be able to save the destructive policy explicitly.'
);
kanboardEmailHistoryAssertSame(
    UninstallDataModel::POLICY_DELETE,
    $uninstallDataModel->getPolicy(),
    'The explicitly saved destructive policy must be returned.'
);
kanboardEmailHistoryAssertTrue(
    $uninstallDataModel->savePolicy(UninstallDataModel::POLICY_RETAIN),
    'The safe retain policy must be restorable.'
);
kanboardEmailHistoryAssertFalse(
    $uninstallDataModel->savePolicy('unknown'),
    'Unknown policy values must be rejected.'
);

$pluginActionId = $container['actionModel']->create(array(
    'project_id' => $projectId,
    'event_name' => TaskModel::EVENT_CLOSE,
    'action_name' => UninstallDataModel::ACTION_NAME,
    'params' => array(
        'email_subject' => 'Retained subject',
        'send_to' => 'all',
        'check_box_include_identifier' => '1',
    ),
));
$otherActionId = $container['actionModel']->create(array(
    'project_id' => $projectId,
    'event_name' => TaskModel::EVENT_CLOSE,
    'action_name' => '\\Kanboard\\Action\\TaskClose',
    'params' => array(),
));

kanboardEmailHistoryAssertTrue($pluginActionId > 0, 'The plugin action fixture must be created.');
kanboardEmailHistoryAssertTrue($otherActionId > 0, 'The unrelated action fixture must be created.');
kanboardEmailHistoryAssertSame(1, $uninstallDataModel->getActiveCount(), 'Only the Email History action class must be counted.');

$retained = $uninstallDataModel->prepareForUninstall(UninstallDataModel::POLICY_RETAIN);
kanboardEmailHistoryAssertSame(1, $retained['active_prepared'], 'The retain policy must disable the Email History action.');
kanboardEmailHistoryAssertSame(1, $retained['saved_total'], 'The retain policy must preserve one complete action record.');
kanboardEmailHistoryAssertSame(0, $uninstallDataModel->getActiveCount(), 'Prepared records must leave the active action table so removal cannot log a missing class.');
kanboardEmailHistoryAssertSame(1, $uninstallDataModel->getSavedCount(), 'Prepared records must remain available for restoration.');
kanboardEmailHistoryAssertTrue(!empty($container['actionModel']->getById($otherActionId)), 'Unrelated automatic-action classes must remain unchanged.');

$probeLogger = new KanboardEmailHistoryUninstallProbeLogger();
$probeContainer = new Container();
$probeContainer['actionModel'] = $container['actionModel'];
$probeContainer['dispatcher'] = new EventDispatcher();
$probeContainer['logger'] = $probeLogger;
$probeContainer['userSession'] = new KanboardEmailHistoryUninstallProbeSession();
$probeManager = new \Kanboard\Core\Action\ActionManager($probeContainer);
$probeManager->register(new \Kanboard\Action\TaskClose($probeContainer));
$probeManager->attachEvents();
kanboardEmailHistoryAssertSame(
    array(),
    $probeLogger->records,
    'A Kanboard action manager without the plugin must not log a missing-action error after retain preparation.'
);

$restoredCount = $uninstallDataModel->restoreSavedActions();
kanboardEmailHistoryAssertSame(1, $restoredCount, 'One retained Email History action must be restored.');
kanboardEmailHistoryAssertSame(1, $uninstallDataModel->getActiveCount(), 'Restoration must reactivate the retained action.');
kanboardEmailHistoryAssertSame(0, $uninstallDataModel->getSavedCount(), 'Restored backup rows must be removed atomically.');

$restoredActions = $container['db']->table(UninstallDataModel::ACTION_TABLE)
    ->eq('action_name', UninstallDataModel::ACTION_NAME)
    ->findAll();
$restoredAction = $container['actionModel']->getById($restoredActions[0]['id']);
kanboardEmailHistoryAssertSame(TaskModel::EVENT_CLOSE, $restoredAction['event_name'], 'Restoration must preserve the event.');
kanboardEmailHistoryAssertSame('Retained subject', $restoredAction['params']['email_subject'], 'Restoration must preserve the subject.');
kanboardEmailHistoryAssertSame('all', $restoredAction['params']['send_to'], 'Restoration must preserve the recipient policy.');
kanboardEmailHistoryAssertSame('1', $restoredAction['params']['check_box_include_identifier'], 'Restoration must preserve optional parameters.');

$deleted = $uninstallDataModel->prepareForUninstall(UninstallDataModel::POLICY_DELETE);
kanboardEmailHistoryAssertSame(1, $deleted['active_deleted'], 'The destructive policy must delete active Email History actions.');
kanboardEmailHistoryAssertSame(0, $uninstallDataModel->getActiveCount(), 'The destructive policy must leave no active Email History actions.');
kanboardEmailHistoryAssertTrue(!empty($container['actionModel']->getById($otherActionId)), 'Destructive preparation must not touch unrelated action classes.');

$secondPluginActionId = $container['actionModel']->create(array(
    'project_id' => $projectId,
    'event_name' => TaskModel::EVENT_CLOSE,
    'action_name' => UninstallDataModel::ACTION_NAME,
    'params' => array('email_subject' => 'Delete retained record', 'send_to' => 'creator'),
));
kanboardEmailHistoryAssertTrue($secondPluginActionId > 0, 'A second plugin action fixture must be created.');
$uninstallDataModel->prepareForUninstall(UninstallDataModel::POLICY_RETAIN);
$deletedSaved = $uninstallDataModel->prepareForUninstall(UninstallDataModel::POLICY_DELETE);
kanboardEmailHistoryAssertSame(1, $deletedSaved['saved_deleted'], 'The destructive policy must also delete retained action records.');
kanboardEmailHistoryAssertSame(0, $uninstallDataModel->getSavedCount(), 'Deleted retained records must not be restorable.');

$pluginSource = file_get_contents($pluginRoot . '/Plugin.php');
$controllerSource = file_get_contents($pluginRoot . '/Controller/EmailHistorySettingsController.php');
$settingsTemplate = file_get_contents($pluginRoot . '/Template/config/uninstall_data.php');
$confirmationTemplate = file_get_contents($pluginRoot . '/Template/config/prepare_confirmation.php');

kanboardEmailHistoryAssertContains("applicationAccessMap->add('EmailHistorySettingsController', '*', Role::APP_ADMIN)", $pluginSource, 'Every settings action must be administrator-only.');
kanboardEmailHistoryAssertContains('checkCSRFForm()', $controllerSource, 'Every policy mutation must enforce a CSRF token.');
kanboardEmailHistoryAssertContains('Prepare for uninstall', $settingsTemplate, 'The explicit pre-uninstall workflow must be visible to administrators.');
kanboardEmailHistoryAssertContains('does not provide plugins with a callback', $settingsTemplate, 'The UI must explain the Kanboard lifecycle limitation.');
kanboardEmailHistoryAssertContains('cannot be undone', $settingsTemplate, 'The destructive setting must explain irreversibility.');
kanboardEmailHistoryAssertContains('name="confirm_delete"', $confirmationTemplate, 'Permanent deletion must require a separate acknowledgement.');

foreach (array('Sqlite.php', 'Mysql.php', 'Postgres.php') as $schemaFile) {
    $schemaSource = file_get_contents($pluginRoot . '/Schema/' . $schemaFile);
    kanboardEmailHistoryAssertContains('const VERSION = 1;', $schemaSource, $schemaFile . ' must support clean and existing installations through migration version 1.');
    kanboardEmailHistoryAssertContains(UninstallDataModel::BACKUP_TABLE, $schemaSource, $schemaFile . ' must create the action backup table.');
    kanboardEmailHistoryAssertContains(UninstallDataModel::BACKUP_PARAMETER_TABLE, $schemaSource, $schemaFile . ' must create the parameter backup table.');
    kanboardEmailHistoryAssertContains('ON DELETE CASCADE', $schemaSource, $schemaFile . ' must clean backup data when its parent record is deleted.');
}

$container['projectModel']->remove($projectId);

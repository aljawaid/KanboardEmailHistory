<?php

/**
 * Preserves or deletes KanboardEmailHistory automatic-action data before uninstall.
 *
 * Related test file: tests/UninstallDataPolicyTest.php.
 */

namespace Kanboard\Plugin\KanboardEmailHistory\Model;

use Kanboard\Core\Base;
use RuntimeException;
use Throwable;

class UninstallDataModel extends Base
{
    const ACTION_NAME = '\\Kanboard\\Plugin\\KanboardEmailHistory\\Action\\EmailTaskHistory';
    const ACTION_TABLE = 'actions';
    const ACTION_PARAMETER_TABLE = 'action_has_params';
    const BACKUP_TABLE = 'kanboard_email_history_action_backups';
    const BACKUP_PARAMETER_TABLE = 'kanboard_email_history_action_backup_params';
    const POLICY_SETTING = 'kanboard_email_history_uninstall_policy';
    const POLICY_RETAIN = 'retain';
    const POLICY_DELETE = 'delete';

    /**
     * Return the saved policy, defaulting safely for existing installations.
     *
     * @return string
     */
    public function getPolicy()
    {
        $policy = $this->configModel->getOption(self::POLICY_SETTING, self::POLICY_RETAIN);

        return $this->isValidPolicy($policy) ? $policy : self::POLICY_RETAIN;
    }

    /**
     * Save an administrator-selected uninstall-data policy.
     *
     * @param  string $policy
     * @return bool
     */
    public function savePolicy($policy)
    {
        if (!$this->isValidPolicy($policy)) {
            return false;
        }

        return $this->configModel->save(array(self::POLICY_SETTING => $policy));
    }

    /**
     * Count active automatic actions owned by this plugin.
     *
     * @return int
     */
    public function getActiveCount()
    {
        return (int) $this->db->table(self::ACTION_TABLE)
            ->eq('action_name', self::ACTION_NAME)
            ->count();
    }

    /**
     * Count automatic actions retained for restoration.
     *
     * @return int
     */
    public function getSavedCount()
    {
        return (int) $this->db->table(self::BACKUP_TABLE)->count();
    }

    /**
     * Apply the saved policy before Kanboard removes the plugin files.
     *
     * @param  string|null $policy
     * @return array
     */
    public function prepareForUninstall($policy = null)
    {
        $policy = $policy === null ? $this->getPolicy() : $policy;

        if (!$this->isValidPolicy($policy)) {
            throw new RuntimeException('Invalid KanboardEmailHistory uninstall-data policy.');
        }

        $this->db->startTransaction();

        try {
            $actions = $this->getActiveActions();
            $savedBefore = $this->getSavedCount();

            if ($policy === self::POLICY_RETAIN) {
                foreach ($actions as $action) {
                    $this->retainAction($action);
                    $this->removeActiveAction($action['id']);
                }

                $result = array(
                    'policy' => $policy,
                    'active_prepared' => count($actions),
                    'saved_total' => $this->getSavedCount(),
                    'active_deleted' => 0,
                    'saved_deleted' => 0,
                );
            } else {
                foreach ($actions as $action) {
                    $this->removeActiveAction($action['id']);
                }

                $this->removeAllSavedActions();
                $result = array(
                    'policy' => $policy,
                    'active_prepared' => 0,
                    'saved_total' => 0,
                    'active_deleted' => count($actions),
                    'saved_deleted' => $savedBefore,
                );
            }

            $this->db->closeTransaction();
            return $result;
        } catch (Throwable $exception) {
            $this->db->cancelTransaction();
            throw $exception;
        }
    }

    /**
     * Restore retained actions to Kanboard's active action tables.
     *
     * @return int
     */
    public function restoreSavedActions()
    {
        $this->db->startTransaction();

        try {
            $backups = $this->db->table(self::BACKUP_TABLE)->asc('id')->findAll();
            $restored = 0;

            foreach ($backups as $backup) {
                if (!$this->db->table(self::ACTION_TABLE)->insert(array(
                    'project_id' => $backup['project_id'],
                    'event_name' => $backup['event_name'],
                    'action_name' => self::ACTION_NAME,
                ))) {
                    throw new RuntimeException('Unable to restore a KanboardEmailHistory automatic action.');
                }

                $actionId = $this->db->getLastId();
                $parameters = $this->db->table(self::BACKUP_PARAMETER_TABLE)
                    ->eq('backup_id', $backup['id'])
                    ->asc('id')
                    ->findAll();

                foreach ($parameters as $parameter) {
                    if (!$this->db->table(self::ACTION_PARAMETER_TABLE)->insert(array(
                        'action_id' => $actionId,
                        'name' => $parameter['name'],
                        'value' => $parameter['value'],
                    ))) {
                        throw new RuntimeException('Unable to restore KanboardEmailHistory action parameters.');
                    }
                }

                if (!$this->db->table(self::BACKUP_TABLE)->eq('id', $backup['id'])->remove()) {
                    throw new RuntimeException('Unable to remove restored KanboardEmailHistory backup data.');
                }

                $restored++;
            }

            $this->db->closeTransaction();
            return $restored;
        } catch (Throwable $exception) {
            $this->db->cancelTransaction();
            throw $exception;
        }
    }

    /**
     * Return active action rows owned by this exact action class.
     *
     * @return array
     */
    private function getActiveActions()
    {
        return $this->db->table(self::ACTION_TABLE)
            ->eq('action_name', self::ACTION_NAME)
            ->asc('id')
            ->findAll();
    }

    /**
     * Copy one active action and all of its parameters into plugin-owned storage.
     *
     * @param  array $action
     * @return void
     */
    private function retainAction(array $action)
    {
        $backupId = $this->db->table(self::BACKUP_TABLE)
            ->eq('original_action_id', $action['id'])
            ->findOneColumn('id');
        $backupValues = array(
            'original_action_id' => $action['id'],
            'project_id' => $action['project_id'],
            'event_name' => $action['event_name'],
            'date_prepared' => time(),
        );

        if ($backupId) {
            if (!$this->db->table(self::BACKUP_TABLE)->eq('id', $backupId)->update($backupValues)) {
                throw new RuntimeException('Unable to update retained KanboardEmailHistory action data.');
            }

            $this->db->table(self::BACKUP_PARAMETER_TABLE)->eq('backup_id', $backupId)->remove();
        } else {
            if (!$this->db->table(self::BACKUP_TABLE)->insert($backupValues)) {
                throw new RuntimeException('Unable to retain KanboardEmailHistory action data.');
            }

            $backupId = $this->db->getLastId();
        }

        $parameters = $this->actionParameterModel->getAllByAction($action['id']);
        foreach ($parameters as $name => $value) {
            if (!$this->db->table(self::BACKUP_PARAMETER_TABLE)->insert(array(
                'backup_id' => $backupId,
                'name' => $name,
                'value' => $value,
            ))) {
                throw new RuntimeException('Unable to retain KanboardEmailHistory action parameters.');
            }
        }
    }

    /**
     * Remove one active action after it has been retained or deletion was selected.
     *
     * @param  int $actionId
     * @return void
     */
    private function removeActiveAction($actionId)
    {
        if (!$this->actionModel->remove($actionId)) {
            throw new RuntimeException('Unable to remove a KanboardEmailHistory automatic action.');
        }
    }

    /**
     * Permanently remove every retained action record.
     *
     * @return void
     */
    private function removeAllSavedActions()
    {
        $backups = $this->db->table(self::BACKUP_TABLE)->findAll();
        foreach ($backups as $backup) {
            if (!$this->db->table(self::BACKUP_TABLE)->eq('id', $backup['id'])->remove()) {
                throw new RuntimeException('Unable to delete retained KanboardEmailHistory action data.');
            }
        }
    }

    /**
     * Validate the only supported policy values.
     *
     * @param  string $policy
     * @return bool
     */
    private function isValidPolicy($policy)
    {
        return in_array($policy, array(self::POLICY_RETAIN, self::POLICY_DELETE), true);
    }
}

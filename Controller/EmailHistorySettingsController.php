<?php

/**
 * Provides administrator controls for safe KanboardEmailHistory uninstall preparation.
 *
 * Related test file: tests/UninstallDataPolicyTest.php.
 */

namespace Kanboard\Plugin\KanboardEmailHistory\Controller;

use Kanboard\Controller\BaseController;
use Kanboard\Core\Controller\AccessForbiddenException;
use Kanboard\Plugin\KanboardEmailHistory\Model\UninstallDataModel;
use Throwable;

class EmailHistorySettingsController extends BaseController
{
    /**
     * Display the uninstall-data policy and current action counts.
     */
    public function index()
    {
        $this->assertAdmin();
        $this->response->html($this->helper->layout->config('kanboardEmailHistory:config/uninstall_data', array(
            'title' => t('Settings') . ' &#10562; ' . t('Email History'),
            'policy' => $this->uninstallDataModel->getPolicy(),
            'policy_retain' => UninstallDataModel::POLICY_RETAIN,
            'policy_delete' => UninstallDataModel::POLICY_DELETE,
            'active_count' => $this->uninstallDataModel->getActiveCount(),
            'saved_count' => $this->uninstallDataModel->getSavedCount(),
        )));
    }

    /**
     * Save the policy without changing any action data.
     */
    public function savePolicy()
    {
        $this->assertPostAdmin();
        $this->checkCSRFForm();
        $values = $this->request->getValues();
        $policy = isset($values[UninstallDataModel::POLICY_SETTING])
            ? $values[UninstallDataModel::POLICY_SETTING]
            : '';

        if (!in_array($policy, array(UninstallDataModel::POLICY_RETAIN, UninstallDataModel::POLICY_DELETE), true)) {
            $this->flash->failure(t('Choose a valid uninstall data policy.'));
        } elseif ($this->uninstallDataModel->savePolicy($policy)) {
            $this->flash->success(t('Uninstall data policy saved.'));
        } else {
            $this->flash->failure(t('Unable to save the uninstall data policy.'));
        }

        $this->redirectToSettings();
    }

    /**
     * Display a policy-specific confirmation before mutating action data.
     */
    public function confirmPreparation()
    {
        $this->assertAdmin();
        $this->response->html($this->template->render('kanboardEmailHistory:config/prepare_confirmation', array(
            'title' => t('Confirm uninstall preparation'),
            'policy' => $this->uninstallDataModel->getPolicy(),
            'policy_retain' => UninstallDataModel::POLICY_RETAIN,
            'policy_delete' => UninstallDataModel::POLICY_DELETE,
            'active_count' => $this->uninstallDataModel->getActiveCount(),
            'saved_count' => $this->uninstallDataModel->getSavedCount(),
        )));
    }

    /**
     * Move or delete this plugin's action rows according to the saved policy.
     */
    public function prepareForUninstall()
    {
        $this->assertPostAdmin();
        $this->checkCSRFForm();
        $policy = $this->uninstallDataModel->getPolicy();

        if (
            $policy === UninstallDataModel::POLICY_DELETE
            && $this->request->getRawValue('confirm_delete') !== '1'
        ) {
            $this->flash->failure(t('Confirm that permanent deletion is understood before continuing.'));
            return $this->redirectToSettings();
        }

        try {
            $result = $this->uninstallDataModel->prepareForUninstall($policy);

            if ($policy === UninstallDataModel::POLICY_RETAIN) {
                $this->flash->success(t(
                    'Prepared %d automatic action(s) for uninstall and retained their configuration.',
                    $result['active_prepared']
                ));
            } else {
                $this->flash->success(t(
                    'Deleted %d active and %d saved automatic action(s).',
                    $result['active_deleted'],
                    $result['saved_deleted']
                ));
            }
        } catch (Throwable $exception) {
            $this->logger->error(__METHOD__ . ': ' . $exception->getMessage());
            $this->flash->failure(t('Unable to prepare automatic actions for uninstall.'));
        }

        $this->redirectToSettings();
    }

    /**
     * Restore previously retained actions after reinstallation or a cancelled uninstall.
     */
    public function restoreSavedActions()
    {
        $this->assertPostAdmin();
        $this->checkCSRFForm();

        try {
            $restored = $this->uninstallDataModel->restoreSavedActions();
            $this->flash->success(t('Restored %d automatic action(s).', $restored));
        } catch (Throwable $exception) {
            $this->logger->error(__METHOD__ . ': ' . $exception->getMessage());
            $this->flash->failure(t('Unable to restore saved automatic actions.'));
        }

        $this->redirectToSettings();
    }

    /**
     * Require both administrator access and a CSRF-protected POST request.
     */
    private function assertPostAdmin()
    {
        $this->assertAdmin();

        if (!$this->request->isPost()) {
            throw new AccessForbiddenException();
        }
    }

    /**
     * Keep controller actions unavailable to non-administrators even if routing changes.
     */
    private function assertAdmin()
    {
        if (!$this->userSession->isAdmin()) {
            throw new AccessForbiddenException();
        }
    }

    /**
     * Return to the plugin settings page.
     */
    private function redirectToSettings()
    {
        $this->response->redirect($this->helper->url->to(
            'EmailHistorySettingsController',
            'index',
            array('plugin' => 'KanboardEmailHistory')
        ));
    }
}

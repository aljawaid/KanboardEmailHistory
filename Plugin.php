<?php

namespace Kanboard\Plugin\KanboardEmailHistory;

use Kanboard\Core\Plugin\Base;
use Kanboard\Core\Security\Role;
use Kanboard\Core\Translator;
use Kanboard\Plugin\KanboardEmailHistory\Action\EmailTaskHistory;

/**
 * Registers the KanboardEmailHistory automatic action and presentation assets.
 *
 * Related test files: tests/EmailTaskHistoryTest.php,
 * tests/KanboardUninstallLifecycleTest.php, and tests/UninstallDataPolicyTest.php.
 */
class Plugin extends Base
{
    public function initialize()
    {
        $this->actionManager->register(new EmailTaskHistory($this->container));
        $this->template->setTemplateOverride('action_creation/event', 'kanboardEmailHistory:action_creation/event');
        $this->template->hook->attach('template:config:sidebar', 'kanboardEmailHistory:config/sidebar');
        $this->hook->on('template:layout:css', array('template' => 'plugins/KanboardEmailHistory/Assets/css/kanboard-email-history.css'));

        // Uninstall preparation changes application-wide action data and is restricted to administrators.
        $this->applicationAccessMap->add('EmailHistorySettingsController', '*', Role::APP_ADMIN);
        $this->route->addRoute('/settings/email-history', 'EmailHistorySettingsController', 'index', 'KanboardEmailHistory');
    }

    public function onStartup()
    {
        Translator::load($this->languageModel->getCurrentLanguage(), __DIR__ . '/Locale');
    }

    public function getPluginName()
    {
        return 'KanboardEmailHistory';
    }

    public function getClasses()
    {
        return array(
            'Plugin\KanboardEmailHistory\Model' => array('UninstallDataModel'),
        );
    }

    public function getPluginDescription()
    {
        return t('This plugin adds an Automatic Action that queues a final email report when a task is closed. Reports include the task description and the comment history permitted for each recipient. Recipient selection, subject composition, duplicate-address protection and task audit comments are configurable.');
    }

    public function getPluginAuthor()
    {
        return 'aljawaid';
    }

    public function getPluginVersion()
    {
        return '2.9.0';
    }

    public function getCompatibleVersion()
    {
        return '>=1.2.54';
    }

    public function getPluginHomepage()
    {
        return 'https://github.com/aljawaid/KanboardEmailHistory';
    }
}

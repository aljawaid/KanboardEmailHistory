<?php

namespace Kanboard\Plugin\KanboardEmailHistory;

use Kanboard\Core\Plugin\Base;
use Kanboard\Core\Translator;
use Kanboard\Plugin\KanboardEmailHistory\Action\EmailTaskHistory;

/**
 * Registers the KanboardEmailHistory automatic action and presentation assets.
 *
 * Related test file: tests/EmailTaskHistoryTest.php.
 */
class Plugin extends Base
{
    public function initialize()
    {
        $this->actionManager->register(new EmailTaskHistory($this->container));
        $this->template->setTemplateOverride('action_creation/event', 'kanboardEmailHistory:action_creation/event');
        $this->hook->on('template:layout:css', array('template' => 'plugins/KanboardEmailHistory/Assets/css/kanboard-email-history.css'));
    }

    public function onStartup()
    {
        Translator::load($this->languageModel->getCurrentLanguage(), __DIR__ . '/Locale');
    }

    public function getPluginName()
    {
        return 'KanboardEmailHistory';
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
        return '2.8.1';
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

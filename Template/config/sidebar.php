<?php

/**
 * Adds the administrator-only Email History settings entry.
 *
 * Related test file: tests/UninstallDataPolicyTest.php.
 */

?>
<li <?= $this->app->checkMenuSelection('EmailHistorySettingsController', 'index', 'KanboardEmailHistory') ?>>
    <?= $this->url->link(t('Email History'), 'EmailHistorySettingsController', 'index', array('plugin' => 'KanboardEmailHistory')) ?>
</li>

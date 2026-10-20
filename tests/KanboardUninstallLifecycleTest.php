<?php

/**
 * Verifies the exact Kanboard 1.2.54 uninstall lifecycle used by the plugin policy.
 *
 * Related production files: Plugin.php, Model/UninstallDataModel.php, and
 * Controller/EmailHistorySettingsController.php.
 */

kanboardEmailHistoryAssertSame('1.2.54', APP_VERSION, 'The uninstall lifecycle contract must be verified against Kanboard 1.2.54.');

$installerSource = file_get_contents($kanboardRoot . '/app/Core/Plugin/Installer.php');
$pluginBaseSource = file_get_contents($kanboardRoot . '/app/Core/Plugin/Base.php');
$pluginControllerSource = file_get_contents($kanboardRoot . '/app/Controller/PluginController.php');
$pluginCommandSource = file_get_contents($kanboardRoot . '/app/Console/PluginUninstallCommand.php');
$actionManagerSource = file_get_contents($kanboardRoot . '/app/Core/Action/ActionManager.php');

kanboardEmailHistoryAssertContains(
    'Tool::removeAllFiles($pluginFolder);',
    $installerSource,
    'Kanboard 1.2.54 must be proven to delete plugin files directly during uninstall.'
);
kanboardEmailHistoryAssertNotContains(
    'function uninstall',
    $pluginBaseSource,
    'Kanboard 1.2.54 must not be represented as providing a plugin uninstall callback.'
);
kanboardEmailHistoryAssertContains(
    '$installer->uninstall($pluginId);',
    $pluginControllerSource,
    'The web uninstall route must delegate directly to the core installer.'
);
kanboardEmailHistoryAssertContains(
    '$installer->uninstall($input->getArgument(\'pluginId\'));',
    $pluginCommandSource,
    'The command-line uninstall route must delegate directly to the same core installer.'
);
kanboardEmailHistoryAssertContains(
    '$this->logger->error(__METHOD__.\': \'.$e->getMessage());',
    $actionManagerSource,
    'Unresolved automatic-action classes must be proven to produce a core log error.'
);

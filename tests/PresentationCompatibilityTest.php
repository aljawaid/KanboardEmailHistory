<?php

/**
 * Verifies responsive, theme-aware form and email presentation safeguards.
 *
 * Related production files: Assets/css/kanboard-email-history.css and the email and action templates.
 */

$stylesheet = file_get_contents($pluginRoot . '/Assets/css/kanboard-email-history.css');
$eventTemplate = file_get_contents($pluginRoot . '/Template/action_creation/event.php');
$paramsTemplate = file_get_contents($pluginRoot . '/Template/action_creation/params.php');
$emailTemplate = file_get_contents($pluginRoot . '/Template/notification/task_create.php');
$footerTemplate = file_get_contents($pluginRoot . '/Template/notification/footer.php');

kanboardEmailHistoryAssertContains('light-dark(', $stylesheet, 'The action form must supply paired light and dark colours.');
kanboardEmailHistoryAssertContains('transition: color var(--kbeh-transition)', $stylesheet, 'Interactive controls must retain smooth hover transitions.');
kanboardEmailHistoryAssertContains('@media screen and (max-width: 768px)', $stylesheet, 'The form must adapt to tablet and mobile widths.');
kanboardEmailHistoryAssertContains('min-height: 44px', $stylesheet, 'Mobile action controls must provide a practical touch target.');
kanboardEmailHistoryAssertContains('@media (prefers-reduced-motion: reduce)', $stylesheet, 'Reduced-motion preferences must be respected.');
kanboardEmailHistoryAssertContains('@media (forced-colors: active)', $stylesheet, 'Forced-colour mode must retain visible boundaries.');
kanboardEmailHistoryAssertNotContains('.pp-red', $stylesheet, 'The focused stylesheet must not retain unused global red utility classes.');

kanboardEmailHistoryAssertContains('kbeh-help-popover', $paramsTemplate, 'The recipient visibility decision must have an in-form helper disclosure.');
kanboardEmailHistoryAssertContains('fa-info-circle', $paramsTemplate, 'The recipient visibility helper must use an identifiable information icon.');
kanboardEmailHistoryAssertContains('only the comments permitted for that recipient', $eventTemplate, 'The action description must explain recipient-specific comment filtering.');
kanboardEmailHistoryAssertContains('accepted by the mail queue', $eventTemplate, 'The action description must distinguish queue acceptance from delivery.');

kanboardEmailHistoryAssertContains('name="color-scheme" content="light dark"', $emailTemplate, 'Generated email must advertise light and dark colour support.');
kanboardEmailHistoryAssertContains('@media screen and (max-width: 600px)', $emailTemplate, 'Generated email must remain readable on narrow clients.');
kanboardEmailHistoryAssertContains("false, '', true", $emailTemplate, 'Recurrence links must be generated as absolute URLs.');
kanboardEmailHistoryAssertContains('rel="noopener noreferrer"', $emailTemplate, 'New-tab recurrence links must not retain opener access or referrer data.');
kanboardEmailHistoryAssertContains('rel="noopener noreferrer"', $footerTemplate, 'New-tab footer links must not retain opener access or referrer data.');
kanboardEmailHistoryAssertContains('$this->text->e($applicationName', $footerTemplate, 'Application branding values must be escaped in generated email.');
kanboardEmailHistoryAssertNotContains('$this->url->absoluteLink', $footerTemplate, 'Footer links must not use the core helper that omits rel protection.');

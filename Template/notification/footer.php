<?php
$brandingInstalled = defined('PLUGINS_DIR') && is_dir(PLUGINS_DIR . DIRECTORY_SEPARATOR . 'ApplicationBranding');
$applicationName = $brandingInstalled ? $this->task->configModel->get('app_rename') : '';
$copyrightFrom = $brandingInstalled ? $this->task->configModel->get('copyright_from') : '';
?>
<footer class="kbeh-footer">
    <hr class="kbeh-divider">
    <span>&copy;&nbsp;
        <?php if ($brandingInstalled): ?>
            <?= $this->text->e($applicationName ?: t('My Workspace')) ?>
            <?= $this->text->e($copyrightFrom ? $copyrightFrom . '-' . date('Y') : date('Y')) ?>
        <?php else: ?>
            <?= t('System Generated Email') ?>
        <?php endif ?>
        <?php if ($this->app->config('application_url') != ''): ?>
            <?php if (isset($task['id'])): ?>
                <?php $taskUrl = $this->url->href('TaskViewController', 'show', array('task_id' => $task['id'], 'project_id' => $task['project_id']), false, '', true) ?>
                - <a href="<?= $this->text->e($taskUrl) ?>" target="_blank" rel="noopener noreferrer"><?= t('View Task') ?></a>
            <?php endif ?>
            <?php $boardUrl = $this->url->href('BoardViewController', 'show', array('project_id' => $task['project_id']), false, '', true) ?>
            - <a href="<?= $this->text->e($boardUrl) ?>" target="_blank" rel="noopener noreferrer"><?= t('View Board') ?></a>
        <?php endif ?>
    </span>
</footer>
</div>
</body>
</html>

<hr/>
<?php if (file_exists('plugins/ApplicationBranding')): ?>
    <span class="">&copy;&nbsp;
        <?php if (!empty($this->task->configModel->get('app_rename'))): ?>
            <?= $this->task->configModel->get('app_rename') ?>
        <?php else: ?>
            <?= t('My Workspace') ?>
        <?php endif ?>
        <?php if (!empty($this->task->configModel->get('copyright_from'))): ?>
            <?= $this->task->configModel->get('copyright_from') ?>-<?= date("Y"); ?>
        <?php else: ?>
            <?= date("Y"); ?>
        <?php endif ?>
    </span>
<?php else: ?>
    <?= t('System Generated Email') ?>&nbsp;
<?php endif ?>

<?php if ($this->app->config('application_url') != ''): ?>
    <?php if (isset($task['id'])): ?>
        - <?= $this->url->absoluteLink(t('View Task'), 'TaskViewController', 'show', array('task_id' => $task['id'], 'project_id' => $task['project_id'])) ?>
    <?php endif ?>
    - <?= $this->url->absoluteLink(t('View Board'), 'BoardViewController', 'show', array('project_id' => $task['project_id'])) ?>
<?php endif ?>
</body>
</html>

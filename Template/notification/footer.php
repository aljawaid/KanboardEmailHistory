<hr/>
<?= t('System Generated Email') ?>&nbsp;

<?php if ($this->app->config('application_url') != ''): ?>
    <?php if (isset($task['id'])): ?>
        - <?= $this->url->absoluteLink(t('View Task'), 'TaskViewController', 'show', array('task_id' => $task['id'], 'project_id' => $task['project_id'])) ?>
    <?php endif ?>
    - <?= $this->url->absoluteLink(t('View Board'), 'BoardViewController', 'show', array('project_id' => $task['project_id'])) ?>
<?php endif ?>
</body>
</html>

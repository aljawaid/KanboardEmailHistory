<html>
<body>
<h2><?= $this->text->e($task['title']) ?> (#<?= $task['id'] ?>)</h2>

<h3><?= t('Summary') ?></h3>
<ul class="">
    <li class="">
        <?= t('Task Status:') ?> <?php if ($task['is_active'] == 1): ?>
            <strong style="text-transform: uppercase; color: green;"><?= t('open') ?></strong>
        <?php else: ?>
            <strong style="text-transform: uppercase; color: red;"><?= t('closed') ?></strong>
        <?php endif ?>
    </li>
    <li class="">
        <?= t('Task N°:') ?> <strong>#<?= $this->text->e($task['id']) ?></strong>
    </li>
    <li class="">
        <?= t('Project:') ?> <strong><?= $this->text->e($task['project_name']) ?></strong>
    </li>
    <?php if (!empty($task['priority'])): ?>
        <li class="">
            <hr style="list-style-type: none;">
            <?= t('Priority:') ?> <strong><?= $this->text->e($task['priority']) ?></strong>
        </li>
    <?php endif ?>
    <?php if (!empty($task['reference'])): ?>
        <li class="">
            <?= t('Reference:') ?> <strong><?= $this->task->renderReference($task) ?></strong>
        </li>
    <?php endif ?>
    <?php if (!empty($task['score'])): ?>
        <li class="">
            <?= t('Complexity:') ?> <strong><?= $this->text->e($task['score']) ?></strong>
        </li>
    <?php endif ?>
    <li class="">
        <hr style="list-style-type: none;">
        <?= t('Created:') . ' ' . $this->dt->datetime($task['date_creation']) ?>
    </li>
    <?php if ($task['date_started']): ?>
        <li class="">
            <?= t('Started:') ?> <strong><?= $this->dt->datetime($task['date_started']) ?></strong>
        </li>
    <?php endif ?>
    <li class="">
        <?= t('Modified:') . ' ' . $this->dt->datetime($task['date_modification']) ?>
    </li>
    <?php if ($task['date_due']): ?>
        <li class="">
            <strong><?= t('Due Date:') . ' ' . $this->dt->datetime($task['date_due']) ?></strong>
        </li>
    <?php endif ?>
    <?php if ($task['date_completed']): ?>
        <li class="">
            <?= t('Completed:') ?> <strong><?= $this->dt->datetime($task['date_completed']) ?></strong>
            <hr style="list-style-type: none;">
        </li>
    <?php endif ?>
    <?php if (!empty($task['creator_username'])): ?>
        <li class="">
            <?= t('Created by %s', $task['creator_name'] ? : $task['creator_username']) ?>
        </li>
    <?php endif ?>
    <li class="">
        <strong>
            <?php if (!empty($task['assignee_username'])): ?>
                <?= t('Assigned to %s', $task['assignee_name'] ? : $task['assignee_username']) ?>
            <?php else: ?>
                <i><?= t('Nobody was assigned to this task') ?></i>
            <?php endif ?>
        </strong>
    </li>
    <li class="">
        <hr style="list-style-type: none;">
        <?= t('Board Column:') ?> <strong><?= $this->text->e($task['column_title']) ?></strong>
    </li>
    <?php if (!empty($task['swimlane_name'])): ?>
        <li class="">
            <?= t('Swimlane:') ?> <strong><?= $this->text->e($task['swimlane_name']) ?></strong>
        </li>
    <?php endif ?>
    <?php if (!empty($task['category_name'])): ?>
        <li class="">
            <?= t('Category:') ?> <strong><?= $this->text->e($task['category_name']) ?></strong>
        </li>
    <?php endif ?>
    <li class="">
        <?= t('Task Position:') . ' ' . $this->text->e($task['position']) ?>
    </li>
    <?php if ($task['recurrence_status'] == \Kanboard\Model\TaskModel::RECURRING_STATUS_PENDING): ?>
        <li class="">
            <hr style="list-style-type: none;">
            <?= t('Recurrence:') ?> <i><?= t('Recurrent task is scheduled to be generated') ?></i>
        </li>
    <?php endif ?>
    <?php if ($task['recurrence_status'] == \Kanboard\Model\TaskModel::RECURRING_STATUS_PROCESSED): ?>
        <li class="">
            <hr style="list-style-type: none;">
            <?= t('Recurrence:') ?> <?= t('Recurrent task has been generated') ?>
        </li>
        <?php if ($task['recurrence_parent'] || $task['recurrence_child']): ?>
            <?php if ($task['recurrence_parent']): ?>
                <li>
                    <?= t('This task was created by: ') ?>
                    <?= $this->url->link('#' . $task['recurrence_parent'], 'TaskViewController', 'show', array('task_id' => $task['recurrence_parent'], 'project_id' => $task['project_id'])) ?>
                </li>
            <?php endif ?>
            <?php if ($task['recurrence_child']): ?>
                <li>
                    <?= t('This task has created this child task: ') ?>
                    <?= $this->url->link('#' . $task['recurrence_child'], 'TaskViewController', 'show', array('task_id' => $task['recurrence_child'], 'project_id' => $task['project_id'])) ?>
                </li>
            <?php endif ?>
        <?php endif ?>
    <?php endif ?>
</ul>
<hr>
<?php if (!empty($task['description'])): ?>
    <h3><?= t('Description') ?></h3>
    <?= $this->text->markdown($task['description'], true) ?>
<?php endif ?>

<hr>

<?php $hasApplicationUrl = $this->app->config('application_url') != '' ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="color-scheme" content="light dark">
    <meta name="supported-color-schemes" content="light dark">
    <style>
        :root {
            color-scheme: light dark;
            supported-color-schemes: light dark;
        }

        body {
            margin: 0;
            padding: 16px;
            color: #202124;
            background: #f2f4f7;
            font-family: Arial, Helvetica, sans-serif;
            line-height: 1.5;
        }

        .kbeh-email {
            box-sizing: border-box;
            width: 100%;
            max-width: 720px;
            margin: 0 auto;
            padding: 24px;
            border: 1px solid #c9d2dc;
            border-radius: 8px;
            background: #ffffff;
        }

        .kbeh-email h2,
        .kbeh-email h3 {
            color: #24384d;
        }

        .kbeh-email ul {
            padding-left: 22px;
        }

        .kbeh-email a {
            color: #2f65a7;
        }

        .kbeh-status-open {
            color: #055d20;
        }

        .kbeh-status-closed {
            color: #8f1d35;
        }

        .kbeh-divider {
            border: 0;
            border-top: 1px solid #d8dee6;
        }

        @media screen and (max-width: 600px) {
            body {
                padding: 8px;
            }

            .kbeh-email {
                padding: 16px;
                border-radius: 4px;
            }
        }

        @media (prefers-color-scheme: dark) {
            body {
                color: #e6edf3;
                background: #171b20;
            }

            .kbeh-email {
                border-color: #4c5968;
                background: #22272e;
            }

            .kbeh-email h2,
            .kbeh-email h3 {
                color: #f0f4f8;
            }

            .kbeh-email a {
                color: #8ab4ea;
            }

            .kbeh-status-open {
                color: #7bd88f;
            }

            .kbeh-status-closed {
                color: #f29aac;
            }

            .kbeh-divider {
                border-top-color: #46515e;
            }
        }
    </style>
</head>
<body>
<div class="kbeh-email">
    <h2><?= $this->text->e($task['title']) ?> (#<?= (int) $task['id'] ?>)</h2>

    <h3><?= t('Summary') ?></h3>
    <ul>
        <li>
            <?= t('Task Status:') ?>
            <?php if ($task['is_active'] == 1): ?>
                <strong class="kbeh-status-open"><?= t('open') ?></strong>
            <?php else: ?>
                <strong class="kbeh-status-closed"><?= t('closed') ?></strong>
            <?php endif ?>
        </li>
        <li><?= t('Task N°:') ?> <strong>#<?= (int) $task['id'] ?></strong></li>
        <li><?= t('Project:') ?> <strong><?= $this->text->e($task['project_name']) ?></strong></li>
        <?php if (!empty($task['priority'])): ?>
            <li><?= t('Priority:') ?> <strong><?= $this->text->e($task['priority']) ?></strong></li>
        <?php endif ?>
        <?php if (!empty($task['reference'])): ?>
            <li><?= t('Reference:') ?> <strong><?= $this->task->renderReference($task) ?></strong></li>
        <?php endif ?>
        <?php if (!empty($task['score'])): ?>
            <li><?= t('Complexity:') ?> <strong><?= $this->text->e($task['score']) ?></strong></li>
        <?php endif ?>
        <li><?= t('Created:') ?> <strong><?= $this->dt->datetime($task['date_creation']) ?></strong></li>
        <?php if (!empty($task['date_started'])): ?>
            <li><?= t('Started:') ?> <strong><?= $this->dt->datetime($task['date_started']) ?></strong></li>
        <?php endif ?>
        <li><?= t('Modified:') ?> <strong><?= $this->dt->datetime($task['date_modification']) ?></strong></li>
        <?php if (!empty($task['date_due'])): ?>
            <li><strong><?= t('Due Date:') ?> <?= $this->dt->datetime($task['date_due']) ?></strong></li>
        <?php endif ?>
        <?php if (!empty($task['date_completed'])): ?>
            <li><?= t('Completed:') ?> <strong><?= $this->dt->datetime($task['date_completed']) ?></strong></li>
        <?php endif ?>
        <?php if (!empty($task['creator_username'])): ?>
            <li><?= t('Created by %s', $task['creator_name'] ?: $task['creator_username']) ?></li>
        <?php endif ?>
        <li>
            <strong>
                <?php if (!empty($task['assignee_username'])): ?>
                    <?= t('Assigned to %s', $task['assignee_name'] ?: $task['assignee_username']) ?>
                <?php else: ?>
                    <i><?= t('Nobody was assigned to this task') ?></i>
                <?php endif ?>
            </strong>
        </li>
        <li><?= t('Board Column:') ?> <strong><?= $this->text->e($task['column_title']) ?></strong></li>
        <?php if (!empty($task['swimlane_name'])): ?>
            <li><?= t('Swimlane:') ?> <strong><?= $this->text->e($task['swimlane_name']) ?></strong></li>
        <?php endif ?>
        <?php if (!empty($task['category_name'])): ?>
            <li><?= t('Category:') ?> <strong><?= $this->text->e($task['category_name']) ?></strong></li>
        <?php endif ?>
        <li><?= t('Task Position:') ?> <?= $this->text->e($task['position']) ?></li>
        <?php if ($task['recurrence_status'] == \Kanboard\Model\TaskModel::RECURRING_STATUS_PENDING): ?>
            <li><?= t('Recurrence:') ?> <i><?= t('Recurrent task is scheduled to be generated') ?></i></li>
        <?php endif ?>
        <?php if ($task['recurrence_status'] == \Kanboard\Model\TaskModel::RECURRING_STATUS_PROCESSED): ?>
            <li><?= t('Recurrence:') ?> <?= t('Recurrent task has been generated') ?></li>
            <?php if (!empty($task['recurrence_parent'])): ?>
                <li>
                    <?= t('This task was created by: ') ?>
                    <?php if ($hasApplicationUrl): ?>
                        <?php $parentUrl = $this->url->href('TaskViewController', 'show', array('task_id' => $task['recurrence_parent'], 'project_id' => $task['project_id']), false, '', true) ?>
                        <a href="<?= $this->text->e($parentUrl) ?>" target="_blank" rel="noopener noreferrer">#<?= (int) $task['recurrence_parent'] ?></a>
                    <?php else: ?>
                        #<?= (int) $task['recurrence_parent'] ?>
                    <?php endif ?>
                </li>
            <?php endif ?>
            <?php if (!empty($task['recurrence_child'])): ?>
                <li>
                    <?= t('This task has created this child task: ') ?>
                    <?php if ($hasApplicationUrl): ?>
                        <?php $childUrl = $this->url->href('TaskViewController', 'show', array('task_id' => $task['recurrence_child'], 'project_id' => $task['project_id']), false, '', true) ?>
                        <a href="<?= $this->text->e($childUrl) ?>" target="_blank" rel="noopener noreferrer">#<?= (int) $task['recurrence_child'] ?></a>
                    <?php else: ?>
                        #<?= (int) $task['recurrence_child'] ?>
                    <?php endif ?>
                </li>
            <?php endif ?>
        <?php endif ?>
    </ul>

    <?php if (!empty($task['description'])): ?>
        <hr class="kbeh-divider">
        <h3><?= t('Description') ?></h3>
        <?= $this->text->markdown($task['description'], true) ?>
    <?php endif ?>

    <hr class="kbeh-divider">

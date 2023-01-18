<div class="page-header step-2-form">
    <h2><?= t('Select an event for this Automatic Action') ?></h2>
</div>

<?php if ($values['action_name'] == '\Kanboard\Plugin\KanboardEmailHistory\Action\EmailTaskHistory'): ?>
    <form method="post" class="eth-form" action="<?= $this->url->href('EmailHistoryController', 'parameters', array('project_id' => $project['id'], 'plugin' => 'KanboardEmailHistory')) ?>">
        <?= $this->form->csrf() ?>

        <?= $this->form->hidden('action_name', $values) ?>

        <i><?= $this->form->label(t('Selected Action'), 'action_name', array('class="selected-labels"')) ?></i>
        <?= $this->form->select('action_name', $available_actions, $values, array(), array('disabled')) ?>

        <?= $this->form->label(t('Event'), 'event_name', array('class="selected-labels"')) ?>
        <?= $this->form->select('event_name', $events, $values) ?>

        <div class="form-help">
            <?= t('When the selected event occurs execute the corresponding action') ?>
        </div>

        <fieldset class="help-text">
            <legend class=""><?= t('Description') ?></legend>
            <p class=""><?= t('This Automatic Action emails a final report once the task is closed. Emails are sent individually detailing the task description and full comment history.') ?></p>
            <p class=""><?= t('A comment is automatically added to the task for each successful email sent.') ?></p>
        </fieldset>

        <?= $this->modal->submitButtons(array(
            'submitLabel' => t('Next step'),
            'cancelLabel' => t('cancel'),
            'color' => 'green-dark',
        )) ?>
    </form>
<?php else: ?>
    <form method="post" action="<?= $this->url->href('ActionCreationController', 'params', array('project_id' => $project['id'])) ?>">
        <?= $this->form->csrf() ?>

        <?= $this->form->hidden('action_name', $values) ?>

        <?= $this->form->label(t('Action'), 'action_name') ?>
        <?= $this->form->select('action_name', $available_actions, $values, array(), array('disabled')) ?>

        <?= $this->form->label(t('Event'), 'event_name') ?>
        <?= $this->form->select('event_name', $events, $values) ?>

        <div class="form-help">
            <?= t('When the selected event occurs execute the corresponding action.') ?>
        </div>

        <?= $this->modal->submitButtons(array(
            'submitLabel' => t('Next step')
        )) ?>
    </form>
<?php endif ?>

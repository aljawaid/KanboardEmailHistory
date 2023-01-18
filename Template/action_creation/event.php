    <h2><?= t('Choose an event') ?></h2>
<div class="page-header step-2-form">
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
            <?= t('When the selected event occurs execute the corresponding action.') ?>
        </div>

        <div class="help-text">
        </div>

        <?= $this->modal->submitButtons(array(
            'submitLabel' => t('Next step')
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

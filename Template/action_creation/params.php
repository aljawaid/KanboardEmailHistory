<div class="page-header">
    <h2><?= t('Define Automatic Action Parameters') ?></h2>
</div>

<form method="post" action="<?= $this->url->href('ActionCreationController', 'save', array('project_id' => $project['id'])) ?>" autocomplete="on">
    <?= $this->form->csrf() ?>

    <?= $this->form->hidden('event_name', $values) ?>
    <?= $this->form->hidden('action_name', $values) ?>

    <i><?= $this->form->label(t('Selected Action'), 'action_name') ?></i>
    <?= $this->form->select('action_name', $available_actions, $values, array(), array('disabled')) ?>

    <i><?= $this->form->label(t('Selected Event'), 'event_name') ?></i>
    <?= $this->form->select('event_name', $events, $values, array(), array('disabled')) ?>

    <?php if ($values['action_name'] == '\Kanboard\Plugin\KanboardEmailHistory\Action\EmailTaskHistory'): ?>

        <fieldset class="param-options" name="param-options">
            <legend><?= t('Options') ?></legend>
            <?php foreach ($action_params as $param_name => $param_desc): ?>
                <?php if ($this->text->contains($param_name, 'column_id')): ?>
                    <?= $this->form->label($param_desc, $param_name) ?>
                    <?= $this->form->select('params['.$param_name.']', $columns_list, $values) ?>
                <?php elseif ($this->text->contains($param_name, 'user_id')): ?>
                    <?= $this->form->label($param_desc, $param_name) ?>
                    <?= $this->form->select('params['.$param_name.']', $users_list, $values) ?>
                <?php elseif ($this->text->contains($param_name, 'project_id')): ?>
                    <?= $this->form->label($param_desc, $param_name) ?>
                    <?= $this->form->select('params['.$param_name.']', $projects_list, $values) ?>
                <?php elseif ($this->text->contains($param_name, 'color_id')): ?>
                    <?= $this->form->colorSelect('params['.$param_name.']', $values) ?>
                <?php elseif ($this->text->contains($param_name, 'category_id')): ?>
                    <?= $this->form->label($param_desc, $param_name) ?>
                    <?= $this->form->select('params['.$param_name.']', $categories_list, $values) ?>
                <?php elseif ($this->text->contains($param_name, 'link_id')): ?>
                    <?= $this->form->label($param_desc, $param_name) ?>
                    <?= $this->form->select('params['.$param_name.']', $links_list, $values) ?>
                <?php elseif ($this->text->contains($param_name, 'check_box')): ?>
                    <?= $this->form->checkbox('params['.$param_name.']', $param_desc, 1, false, 'params-'.$param_name.'') ?>
                <?php elseif ($param_name === 'priority'): ?>
                    <?= $this->form->label($param_desc, $param_name) ?>
                    <?= $this->form->select('params['.$param_name.']', $priorities_list, $values) ?>
                <?php elseif ($this->text->contains($param_name, 'duration')): ?>
                    <?= $this->form->label($param_desc, $param_name) ?>
                    <?= $this->form->number('params['.$param_name.']', $values) ?>
                <?php elseif ($this->text->contains($param_name, 'swimlane_id')): ?>
                    <?= $this->form->label($param_desc, $param_name) ?>
                    <?= $this->form->select('params['.$param_name.']', $swimlane_list, $values) ?>
                <?php elseif (is_array($param_desc) && ($param_name = 'send_to')): ?>
                    <?= $this->form->label(t('Email Recipient(s)'), $param_name) ?>
                    <?= $this->form->select('params['.$param_name.']', $param_desc, $values) ?>
                <?php elseif (is_array($param_desc) && (!$param_name = 'send_to')): ?>
                    <?= $this->form->label(ucfirst($param_name), $param_name) ?>
                    <?= $this->form->select('params['.$param_name.']', $param_desc, $values) ?>
                <?php else: ?>
                    <?= $this->form->label($param_desc, $param_name) ?>
                    <?= $this->form->text('params['.$param_name.']', $values, array(), array('placeholder="'. t('Activity Report') .'"'), 'subject-input') ?>
                    <?php if ($param_name = 'subject'): ?>
                        <div class="form-help form-help-subject">
                            <?= t('If left blank then "Activity Report" is used as the subject') ?>
                        </div>
                    <?php endif ?>
                <?php endif ?>
            <?php endforeach ?>
        </fieldset>

    <?php else: ?>

        <fieldset class="param-options" name="param-options">
            <legend><?= t('Options') ?></legend>
            <?php foreach ($action_params as $param_name => $param_desc): ?>
                <?php if ($this->text->contains($param_name, 'column_id')): ?>
                    <?= $this->form->label($param_desc, $param_name) ?>
                    <?= $this->form->select('params['.$param_name.']', $columns_list, $values) ?>
                <?php elseif ($this->text->contains($param_name, 'user_id')): ?>
                    <?= $this->form->label($param_desc, $param_name) ?>
                    <?= $this->form->select('params['.$param_name.']', $users_list, $values) ?>
                <?php elseif ($this->text->contains($param_name, 'project_id')): ?>
                    <?= $this->form->label($param_desc, $param_name) ?>
                    <?= $this->form->select('params['.$param_name.']', $projects_list, $values) ?>
                <?php elseif ($this->text->contains($param_name, 'color_id')): ?>
                    <?= $this->form->colorSelect('params['.$param_name.']', $values) ?>
                <?php elseif ($this->text->contains($param_name, 'category_id')): ?>
                    <?= $this->form->label($param_desc, $param_name) ?>
                    <?= $this->form->select('params['.$param_name.']', $categories_list, $values) ?>
                <?php elseif ($this->text->contains($param_name, 'link_id')): ?>
                    <?= $this->form->label($param_desc, $param_name) ?>
                    <?= $this->form->select('params['.$param_name.']', $links_list, $values) ?>
                <?php elseif ($this->text->contains($param_name, 'check_box')): ?>
                    <?= $this->form->checkbox('params['.$param_name.']', $param_desc, 1, false, 'params-'.$param_name.'') ?>
                <?php elseif ($param_name === 'priority'): ?>
                    <?= $this->form->label($param_desc, $param_name) ?>
                    <?= $this->form->select('params['.$param_name.']', $priorities_list, $values) ?>
                <?php elseif ($this->text->contains($param_name, 'duration')): ?>
                    <?= $this->form->label($param_desc, $param_name) ?>
                    <?= $this->form->number('params['.$param_name.']', $values) ?>
                <?php elseif ($this->text->contains($param_name, 'swimlane_id')): ?>
                    <?= $this->form->label($param_desc, $param_name) ?>
                    <?= $this->form->select('params['.$param_name.']', $swimlane_list, $values) ?>
                <?php elseif (is_array($param_desc)): ?>
                    <?= $this->form->label(ucfirst($param_name), $param_name) ?>
                    <?= $this->form->select('params['.$param_name.']', $param_desc, $values) ?>
                <?php else: ?>
                    <?= $this->form->label($param_desc, $param_name) ?>
                    <?= $this->form->text($param_name, $values) ?>
                <?php endif ?>
            <?php endforeach ?>
        </fieldset>

    <?php endif ?>

    <?= $this->modal->submitButtons() ?>
</form>

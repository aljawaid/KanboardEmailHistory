<details class="accordion-section" <?= empty($comments) ? '' : 'open' ?>>
    <summary class="accordion-title acc-comments-title">
        <h3><?= t('Comments') ?></h3>
        <?php if (! empty($comments)): ?>
            <span class="">(<?= count($comments) ?>)</span>
        <?php endif ?>
    </summary>
    <div class="accordion-content comments" id="comments">
        <?php if (empty($comments)): ?>
            <hr>
            <div class="no-data"><i><?= t('No comments') ?></i></div>
        <?php endif ?>
        
        <hr>
        <?php foreach ($comments as $comment): ?>
            <?= $this->render('kanboardEmailHistory:comment/show', array(
                'comment'   => $comment,
                'task'      => $task,
                'project'   => $project,
                'editable'  => false,
                'is_public' => isset($is_public) && $is_public,
            )) ?>
        <?php endforeach ?>

    </div>
</details>

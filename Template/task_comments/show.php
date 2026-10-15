<section class="kbeh-comments" aria-labelledby="kbeh-comments-heading">
    <h3 id="kbeh-comments-heading">
        <?= t('Comments') ?>
        <?php if (!empty($comments)): ?>
            (<?= count($comments) ?>)
        <?php endif ?>
    </h3>

    <?php if (empty($comments)): ?>
        <p><i><?= t('No comments available for this recipient') ?></i></p>
    <?php else: ?>
        <?php foreach ($comments as $comment): ?>
            <?= $this->render('kanboardEmailHistory:comment/show', array(
                'comment' => $comment,
                'task' => $task,
                'project' => $project,
                'editable' => false,
                'is_public' => isset($is_public) && $is_public,
            )) ?>
        <?php endforeach ?>
    <?php endif ?>
</section>
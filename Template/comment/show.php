<div class="comment <?= isset($preview) ? 'comment-preview' : '' ?>" id="comment-<?= $comment['id'] ?>">
    <div class="comment-title">
        <?php if (!empty($comment['username'])): ?>
            <strong class="comment-username"><?= $this->text->e($comment['name'] ? : $comment['username']) ?></strong>
        <?php endif ?>
        <small class="comment-date"><?= t('Created at:') ?>&nbsp;
            <kbd class="comment-created">
                <?= $this->dt->datetime($comment['date_creation']) ?><abbr title="<?= t('Local Time') ?>"><?= t('LT') ?></abbr>
            </kbd>
        </small>
        <small class="comment-date updated-comment"><?= t('Updated at:') ?>&nbsp;
            <kbd class="comment-updated">
                <?= $this->dt->datetime($comment['date_modification']) ?><abbr title="<?= t('Local Time') ?>"><?= t('LT') ?></abbr>
            </kbd>
        </small>
        <small class="comment-date comment-id">
            <i class="fa fa-comment-o fa-fw"></i> <?= t('ID: ') ?> <kbd class="comment-created"><?= $this->text->e($comment['id']) ?></kbd>
        </small>
    </div>
    <div class="comment-content">
        <div class="markdown">
            <?= $this->text->markdown($comment['comment'], isset($is_public) && $is_public) ?>
        </div>
    </div>
</div>
<hr>

<article class="kbeh-comment" id="comment-<?= (int) $comment['id'] ?>">
    <div class="kbeh-comment-title">
        <?php if (!empty($comment['username'])): ?>
            <strong><?= $this->text->e($comment['name'] ?: $comment['username']) ?></strong>
        <?php endif ?>
        <small>
            <?= t('Created at:') ?> <?= $this->dt->datetime($comment['date_creation']) ?>
            <abbr title="<?= t('Local Time') ?>"><?= t('LT') ?></abbr>
        </small>
        <?php if ($comment['date_modification'] > $comment['date_creation']): ?>
            <small>
                <?= t('Updated at:') ?> <?= $this->dt->datetime($comment['date_modification']) ?>
                <abbr title="<?= t('Local Time') ?>"><?= t('LT') ?></abbr>
            </small>
        <?php endif ?>
        <small><?= t('ID: ') ?><?= (int) $comment['id'] ?></small>
    </div>
    <div class="kbeh-comment-content">
        <?= $this->text->markdown($comment['comment'], isset($is_public) && $is_public) ?>
    </div>
</article>
<hr class="kbeh-divider">

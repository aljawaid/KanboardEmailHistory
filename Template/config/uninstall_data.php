<?php

/**
 * Displays the uninstall-data policy, consequences, counts, and preparation controls.
 *
 * Related test file: tests/UninstallDataPolicyTest.php.
 */

?>
<div class="kbeh-settings">
    <h2><?= t('Email History uninstall data') ?></h2>

    <p><?= t('Kanboard removes plugin files without running plugin cleanup code. Save a policy, then use Prepare for uninstall before uninstalling this plugin.') ?></p>

    <details class="kbeh-help-popover">
        <summary>
            <i class="fa fa-info-circle" aria-hidden="true"></i>
            <span><?= t('Why preparation is required') ?></span>
        </summary>
        <div class="kbeh-help-popover-content">
            <?= t('Kanboard 1.2.54 does not provide plugins with a callback before their files are removed. The saved policy is applied only when an administrator runs Prepare for uninstall on this page.') ?>
        </div>
    </details>

    <div class="kbeh-settings-summary" aria-label="<?= t('Automatic action summary') ?>">
        <div>
            <span><?= t('Active automatic actions') ?></span>
            <strong><?= (int) $active_count ?></strong>
        </div>
        <div>
            <span><?= t('Saved automatic actions') ?></span>
            <strong><?= (int) $saved_count ?></strong>
        </div>
    </div>

    <form method="post" action="<?= $this->url->href('EmailHistorySettingsController', 'savePolicy', array('plugin' => 'KanboardEmailHistory')) ?>">
        <?= $this->form->csrf() ?>
        <fieldset>
            <legend><?= t('Uninstall data policy') ?></legend>

            <label class="kbeh-policy-option" for="kbeh-policy-retain">
                <input
                    type="radio"
                    id="kbeh-policy-retain"
                    name="kanboard_email_history_uninstall_policy"
                    value="<?= $this->text->e($policy_retain) ?>"
                    <?= $policy === $policy_retain ? 'checked' : '' ?>
                >
                <strong><?= t('Disable and retain automatic actions') ?></strong>
                <p><?= t('Recommended. Moves this plugin\'s automatic actions out of Kanboard\'s active action list while preserving their events, recipients, subjects and other parameters for restoration after reinstallation.') ?></p>
            </label>

            <label class="kbeh-policy-option kbeh-policy-option-danger" for="kbeh-policy-delete">
                <input
                    type="radio"
                    id="kbeh-policy-delete"
                    name="kanboard_email_history_uninstall_policy"
                    value="<?= $this->text->e($policy_delete) ?>"
                    <?= $policy === $policy_delete ? 'checked' : '' ?>
                >
                <strong><?= t('Delete automatic actions permanently') ?></strong>
                <p class="kbeh-danger-text"><?= t('Deletes this plugin\'s active and saved automatic-action records. This cannot be undone.') ?></p>
            </label>
        </fieldset>

        <button type="submit" class="btn"><?= t('Save policy') ?></button>
    </form>

    <div class="kbeh-settings-actions">
        <a
            class="btn btn-red js-modal-medium"
            href="<?= $this->url->href('EmailHistorySettingsController', 'confirmPreparation', array('plugin' => 'KanboardEmailHistory')) ?>"
            rel="noopener noreferrer"
        ><?= t('Prepare for uninstall') ?></a>

        <?php if ($saved_count > 0): ?>
            <form method="post" action="<?= $this->url->href('EmailHistorySettingsController', 'restoreSavedActions', array('plugin' => 'KanboardEmailHistory')) ?>">
                <?= $this->form->csrf() ?>
                <button type="submit" class="btn"><?= t('Restore saved actions') ?></button>
            </form>
        <?php endif ?>
    </div>
</div>

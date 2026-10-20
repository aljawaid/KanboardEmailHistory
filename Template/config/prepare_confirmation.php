<?php

/**
 * Confirms the selected retain or irreversible delete policy before preparation.
 *
 * Related test file: tests/UninstallDataPolicyTest.php.
 */

?>
<div class="kbeh-settings">
    <h2><?= t('Confirm uninstall preparation') ?></h2>

    <p><?= t('Run this step before uninstalling KanboardEmailHistory. Kanboard\'s Uninstall button will not run it automatically.') ?></p>

    <?php if ($policy === $policy_retain): ?>
        <p><?= t('The retain policy will disable %d automatic action(s) and preserve their configuration for restoration.', $active_count) ?></p>
    <?php else: ?>
        <p class="kbeh-danger-text"><?= t('The delete policy will permanently delete %d active and %d saved automatic action(s). This cannot be undone.', $active_count, $saved_count) ?></p>
    <?php endif ?>

    <form method="post" action="<?= $this->url->href('EmailHistorySettingsController', 'prepareForUninstall', array('plugin' => 'KanboardEmailHistory')) ?>">
        <?= $this->form->csrf() ?>

        <?php if ($policy === $policy_delete): ?>
            <label>
                <input type="checkbox" name="confirm_delete" value="1" required>
                <?= t('I understand that deletion is permanent and cannot be undone.') ?>
            </label>
        <?php endif ?>

        <div class="form-actions">
            <button type="submit" class="btn btn-red"><?= t('Prepare for uninstall') ?></button>
            <button type="button" class="btn js-modal-close"><?= t('Cancel') ?></button>
        </div>
    </form>
</div>

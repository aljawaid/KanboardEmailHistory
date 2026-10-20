<?php

/**
 * KanboardEmailHistory MySQL schema migrations.
 *
 * Related test files: tests/UninstallDataPolicyTest.php and tests/database_migrations.php.
 */

namespace Kanboard\Plugin\KanboardEmailHistory\Schema;

use PDO;

const VERSION = 1;

function version_1(PDO $pdo)
{
    // Retain action identity and event configuration outside Kanboard's active action table.
    $pdo->exec('CREATE TABLE IF NOT EXISTS kanboard_email_history_action_backups (
        id INT NOT NULL AUTO_INCREMENT,
        original_action_id INT NOT NULL,
        project_id INT NOT NULL,
        event_name VARCHAR(255) NOT NULL,
        date_prepared BIGINT NOT NULL,
        PRIMARY KEY(id),
        UNIQUE KEY kbeh_action_backup_original_idx (original_action_id),
        CONSTRAINT kbeh_action_backup_project_fk FOREIGN KEY(project_id) REFERENCES projects(id) ON DELETE CASCADE
    ) ENGINE=InnoDB CHARSET=utf8mb4');

    // Retain every action parameter and remove it automatically with its backup record.
    $pdo->exec('CREATE TABLE IF NOT EXISTS kanboard_email_history_action_backup_params (
        id INT NOT NULL AUTO_INCREMENT,
        backup_id INT NOT NULL,
        name VARCHAR(255) NOT NULL,
        value TEXT NOT NULL,
        PRIMARY KEY(id),
        UNIQUE KEY kbeh_action_backup_param_idx (backup_id, name),
        CONSTRAINT kbeh_action_backup_param_fk FOREIGN KEY(backup_id) REFERENCES kanboard_email_history_action_backups(id) ON DELETE CASCADE
    ) ENGINE=InnoDB CHARSET=utf8mb4');
}

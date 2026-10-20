<?php

/**
 * KanboardEmailHistory SQLite schema migrations.
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
        id INTEGER PRIMARY KEY,
        original_action_id INTEGER NOT NULL UNIQUE,
        project_id INTEGER NOT NULL,
        event_name TEXT NOT NULL,
        date_prepared INTEGER NOT NULL,
        FOREIGN KEY(project_id) REFERENCES projects(id) ON DELETE CASCADE
    )');

    // Retain every action parameter and remove it automatically with its backup record.
    $pdo->exec('CREATE TABLE IF NOT EXISTS kanboard_email_history_action_backup_params (
        id INTEGER PRIMARY KEY,
        backup_id INTEGER NOT NULL,
        name TEXT NOT NULL,
        value TEXT NOT NULL,
        UNIQUE(backup_id, name),
        FOREIGN KEY(backup_id) REFERENCES kanboard_email_history_action_backups(id) ON DELETE CASCADE
    )');
}

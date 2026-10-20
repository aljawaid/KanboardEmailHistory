<?php

/**
 * KanboardEmailHistory PostgreSQL schema migrations.
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
        id SERIAL PRIMARY KEY,
        original_action_id INTEGER NOT NULL UNIQUE,
        project_id INTEGER NOT NULL,
        event_name VARCHAR(255) NOT NULL,
        date_prepared BIGINT NOT NULL,
        FOREIGN KEY(project_id) REFERENCES projects(id) ON DELETE CASCADE
    )');

    // Retain every action parameter and remove it automatically with its backup record.
    $pdo->exec('CREATE TABLE IF NOT EXISTS kanboard_email_history_action_backup_params (
        id SERIAL PRIMARY KEY,
        backup_id INTEGER NOT NULL,
        name VARCHAR(255) NOT NULL,
        value TEXT NOT NULL,
        UNIQUE(backup_id, name),
        FOREIGN KEY(backup_id) REFERENCES kanboard_email_history_action_backups(id) ON DELETE CASCADE
    )');
}

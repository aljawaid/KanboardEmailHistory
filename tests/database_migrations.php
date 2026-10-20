<?php

// phpcs:ignoreFile PSR1.Files.SideEffects.FoundWithSymbols -- This executable migration test creates an isolated database fixture.

/**
 * Verifies clean and repeat KanboardEmailHistory migrations on each supported database.
 *
 * Related production files: Schema/Sqlite.php, Schema/Mysql.php, and Schema/Postgres.php.
 */

$driver = isset($argv[1]) ? strtolower($argv[1]) : '';
$supportedDrivers = array('sqlite', 'mysql', 'postgres');

if (!in_array($driver, $supportedDrivers, true)) {
    fwrite(STDERR, "Usage: php tests/database_migrations.php <sqlite|mysql|postgres>\n");
    exit(2);
}

$dsn = getenv('KBEH_TEST_DSN');
$user = getenv('KBEH_TEST_USER') ?: null;
$password = getenv('KBEH_TEST_PASSWORD') ?: null;

if ($driver === 'sqlite') {
    $dsn = 'sqlite::memory:';
}

if (!$dsn) {
    throw new RuntimeException('KBEH_TEST_DSN is required for ' . $driver . '.');
}

$pdo = new PDO($dsn, $user, $password, array(
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
));

if ($driver === 'sqlite') {
    $pdo->exec('PRAGMA foreign_keys = ON');
}

$pdo->exec('DROP TABLE IF EXISTS kanboard_email_history_action_backup_params');
$pdo->exec('DROP TABLE IF EXISTS kanboard_email_history_action_backups');
$pdo->exec('DROP TABLE IF EXISTS projects');

if ($driver === 'mysql') {
    $pdo->exec('CREATE TABLE projects (id INT NOT NULL PRIMARY KEY) ENGINE=InnoDB CHARSET=utf8mb4');
} elseif ($driver === 'postgres') {
    $pdo->exec('CREATE TABLE projects (id INTEGER NOT NULL PRIMARY KEY)');
} else {
    $pdo->exec('CREATE TABLE projects (id INTEGER NOT NULL PRIMARY KEY)');
}

$schemaFile = dirname(__DIR__) . '/Schema/' . ucfirst($driver) . '.php';
require $schemaFile;

$migration = '\\Kanboard\\Plugin\\KanboardEmailHistory\\Schema\\version_1';
$versionConstant = '\\Kanboard\\Plugin\\KanboardEmailHistory\\Schema\\VERSION';

if (!function_exists($migration) || !defined($versionConstant) || constant($versionConstant) !== 1) {
    throw new RuntimeException($driver . ' migration version 1 is not available.');
}

$migration($pdo);
$pdo->exec('INSERT INTO projects (id) VALUES (1)');

$insertBackup = $pdo->prepare('INSERT INTO kanboard_email_history_action_backups
    (original_action_id, project_id, event_name, date_prepared) VALUES (?, ?, ?, ?)');
$insertBackup->execute(array(44, 1, 'task.close', 1792342800));
$backupId = (int) $pdo->query('SELECT id FROM kanboard_email_history_action_backups WHERE original_action_id = 44')->fetchColumn();

$insertParameter = $pdo->prepare('INSERT INTO kanboard_email_history_action_backup_params
    (backup_id, name, value) VALUES (?, ?, ?)');
$insertParameter->execute(array($backupId, 'send_to', 'all'));

// Reapplying the additive migration represents an existing installation and must preserve its retained data.
$migration($pdo);

$backupCount = (int) $pdo->query('SELECT COUNT(*) FROM kanboard_email_history_action_backups')->fetchColumn();
$parameterCount = (int) $pdo->query('SELECT COUNT(*) FROM kanboard_email_history_action_backup_params')->fetchColumn();
if ($backupCount !== 1 || $parameterCount !== 1) {
    throw new RuntimeException($driver . ' repeat migration did not preserve retained action data.');
}

$pdo->exec('DELETE FROM projects WHERE id = 1');
$backupCount = (int) $pdo->query('SELECT COUNT(*) FROM kanboard_email_history_action_backups')->fetchColumn();
$parameterCount = (int) $pdo->query('SELECT COUNT(*) FROM kanboard_email_history_action_backup_params')->fetchColumn();
if ($backupCount !== 0 || $parameterCount !== 0) {
    throw new RuntimeException($driver . ' foreign-key cleanup did not remove dependent retained data.');
}

echo 'KanboardEmailHistory ' . $driver . ' migration checks passed.' . PHP_EOL;

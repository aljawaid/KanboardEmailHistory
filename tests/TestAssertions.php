<?php

/**
 * Provides assertion helpers for KanboardEmailHistory's focused regression tests.
 *
 * Related test runner: tests/run.php.
 */
function kanboardEmailHistoryAssertTrue($condition, $message)
{
    if (!$condition) {
        throw new RuntimeException($message);
    }
}

function kanboardEmailHistoryAssertFalse($condition, $message)
{
    kanboardEmailHistoryAssertTrue(!$condition, $message);
}

function kanboardEmailHistoryAssertSame($expected, $actual, $message)
{
    if ($expected !== $actual) {
        throw new RuntimeException($message . ' Expected ' . var_export($expected, true) . ', got ' . var_export($actual, true) . '.');
    }
}

function kanboardEmailHistoryAssertContains($needle, $haystack, $message)
{
    kanboardEmailHistoryAssertTrue(strpos($haystack, $needle) !== false, $message);
}

function kanboardEmailHistoryAssertNotContains($needle, $haystack, $message)
{
    kanboardEmailHistoryAssertFalse(strpos($haystack, $needle) !== false, $message);
}

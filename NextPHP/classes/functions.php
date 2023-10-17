<?php
//TODO: move the helper functions into this file


/**
 * @param mixed $mode DebugModes::MODE
 * @param mixed $var variable to debug
 * @param string|null $message debug message
 * @return void
 */
function debug(mixed $mode, mixed $var, string|null $message): void {
    if ($mode == DebugModes::ECHO) {
        echo "DEBUG: " . $message . "\n";
    } elseif ($mode == DebugModes::COUNT) {
        $count = count($var);
        echo "DEBUG: " .  $count . "\n";
    } elseif ($mode == DebugModes::DUMP) {
        echo "DEBUG: " . var_dump($var) . "\n"; //this one's pretty much useless as var_dump is shorter anyways.
    }
}
enum DebugModes
{
    case ECHO;
    case DUMP;
    case COUNT;
}


<?php
//TODO: move the helper functions into this file

function debug(mixed $mode, mixed $var, string|null $message) {
    if ($mode == DebugModes::ECHO) {
        echo "DEBUG: " . $message . "\n";
    } elseif ($mode == DebugModes::COUNT) {
        $count = count($var);
        echo "DEBUG: " .  $count . "\n";
    } elseif ($mode == DebugModes::VARDUMP) {
        echo "DEBUG: " . var_dump($var) . "\n"; //this one's pretty much useless as var_dump is shorter anyways.
    }
}
enum DebugModes
{
    case ECHO;
    case VARDUMP;
    case COUNT;
}


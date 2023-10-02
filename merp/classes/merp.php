<?php

namespace classes;

class merp
{
    public static function main($args) {
        if (count($args) > 1) {
            echo "Usage: phpmerp [script]\n"; //this is how you call stuff appearently
            exit(64);
        } elseif (count($args) === 1) {
            self::runFile($args[0]);
        } else {
            self::runPrompt();
        }
    }
    public static function runFile($path){

    }
    public static function runPrompt(){

    }
}
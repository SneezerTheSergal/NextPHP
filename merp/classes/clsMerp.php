<?php

namespace classes;
require_once "clsScanner.php";
use mysql_xdevapi\Exception;

class clsMerp
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
        try {
            $bytes = file_get_contents($path);
            self::run($bytes);
        } catch (Exception $e) {
            echo "Error reading file" . $e->getMessage() . "\n";
        }
    }
    public static function runPrompt(){
        try {
            fgets(STDIN);
            while (true) {
                echo "> ";
                $line = trim(fgets(STDIN));
                if ($line === "exit") {
                    break;
                }
            }
        } catch (Exception $e) {
            echo "Error reading file" . $e->getMessage(). "\n";
        }
    }
    private static function run($source) {
        $clsScanner= new clsScanner($source);

        $tokens[] = $clsScanner->scanTokens();

        foreach ($tokens as $token) {
            echo $token;
        }

    }
}
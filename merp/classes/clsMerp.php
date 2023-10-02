<?php

namespace classes;
require_once "clsScanner.php";

use http\Message;
use mysql_xdevapi\Exception;

class clsMerp
{
    private static $hadError = false;
    public static function main($args) {
        if (count($args) > 1) {
            echo "Usage: phpmerp [script]\n"; //this is how you call stuff appearently
            exit(64);
        } elseif (count($args) === 1) { //args = file name, can't be more than 1 at a time.
            self::runFile($args[0]);
        } else {
            self::runPrompt();
        }
    }
    public static function runFile($path){
        try {
            $bytes = file_get_contents($path);
            self::run($bytes);
            if (self::$hadError) {
                exit(65);
            }
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
                self::run($line);
                self::$hadError = false;
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

    /**
     * @param int $line
     * @param string $message
     * @return void
     */
    public static function error($line, $message ) {
        self::report($line, '', $message);
    }

    /**
     * @param int $line
     * @param string $where
     * @param string $message
     */
    private static function report($line, $where, $message) {
        fwrite(STDERR, "[line" . $line . "] ERROR".  $where . ": " . $message);
        self::$hadError = true;
    }
}
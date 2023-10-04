<?php

namespace MyLang;
require_once "classes/clsScanner.php";
require_once "classes/functions.php";


use classes\clsScanner;
use mysql_xdevapi\Exception;

class clsMain
{
    const RUN_PATH = "MyLang/runFiles/";
    private static $hadError = false;
    public function __construct($fn) { //construct variables
        $this->fn = $fn;
    }
    public static function main($args) {
        if (count($args) > 2) {
            echo "Usage: php /MyLang/interpreter.php name of script in runFiles\n"; //this is how you call stuff appearently
            foreach ($args as $arg) {
//                echo "debug: " . $arg . "\n";
                debug(\DebugModes::ECHO, $arg, null);
            }
            debug(\DebugModes::COUNT, $args, null);

            exit(64);
        } elseif (count($args) === 2) { //args = file name, can't be more than 1 at a time.
            self::runFile(self::RUN_PATH . $args[1]);
        } else {
            self::runPrompt();
        }
    }
    public static function runFile($path){
        try {
            $bytes = file_get_contents($path);
            self::run($bytes);
            debug(\DebugModes::DUMP, $bytes, null);
            debug(\DebugModes::ECHO, null, "file went through"); //unnecessarily long debug func
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
        //debug(\DebugModes::DUMP, $source, null);
        $clsScanner= new clsScanner($source); // source seems to not be given to clsScanner
        echo " starting the scanning process... \n";
        $tokens[] = $clsScanner->scanTokens();
        echo " scanned tokens! \n";

        foreach ($tokens as $token) {
            foreach ($token as $tokens) {
                var_dump($tokens);
            }

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
        fwrite(STDERR, "[error on line " . $line . "] ERROR".  $where . ": " . $message);
        self::$hadError = true;
    }
}
<?php
require_once "MyLang/clsMain.php";
//require_once "tool/clsGenerateAst.php";
$fileToRun = $argv;
MyLang\clsMain::main($fileToRun);
//tool\GenerateAst::main($argv);
?>

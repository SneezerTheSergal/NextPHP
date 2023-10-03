<?php

namespace tool;
public class GenerateAst
{
    public function main($args) {
        if ($args != 1) {
            \classes\clsMain::error(1, "Usage: generateAst [output dir]");
            exit(64);
        }
        $outputDir = $args[0];
    }
}
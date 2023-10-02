<?php

namespace classes;

class clsScanner
{
    private $source;
    private $tokens = [];
    public function __construct($source) {
        $this->$source = $source;
    }

    public function scanTokens() {
        return $this->tokens;
    }

}
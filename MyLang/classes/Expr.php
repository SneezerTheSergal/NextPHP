<?php

namespace classes;

abstract class clsExpr
{

}

class binary extends clsExpr {
    public $left;
    public $operator;
    public $right;

    public function __construct($left, $operator, $right) {
        $this->left = $left;
        $this->right = $right;
        $this->operator = $operator;
    }
}
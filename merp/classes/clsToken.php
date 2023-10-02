<?php

namespace classes;
require_once "clsTokenType.php";
class clsToken
{
   private $tokenType;
   private string $lexeme; //lexemeeee marioooo
   private object $literal;
   private int $line;


   public function token($tokenType, $lexeme, $literal, $line) {
       $this->tokenType = $tokenType;
       $this->lexeme = $lexeme;
       $this->literal = $literal;
       $this->line = $line;
   }

   public function toString() {
       return $this->tokenType . " " . $this->lexeme . " " . $this->literal;
   }


}
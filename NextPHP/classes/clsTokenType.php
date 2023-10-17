<?php

namespace classes;
enum clsTokenType
{
    // single char tokens -> simple chars
    case  LEFT_PAREN ;
    case RIGHT_PAREN ;
    case  LEFT_BRACE ;
    case  RIGHT_BRACE ;
    case  COMMA ;
    case  DOT ;
    case  MINUS ;
    case  PLUS ;
    case  SEMICOLON ;
    case  SLASH ;
    case  STAR ;
    case  MODULO ;

    //1 to 2 char tokens -> if you ever want to add more add here
    case  BANG_EQUAL ;
    case  BANG ;
    case  EQUAL ;
    case  EQUAL_EQUAL ;
    case  GREATER ;
    case  GREATER_EQUAL ;
    case  LESS ;
    case  LESS_EQUAL ;

    //literals -> add literals here
    case  IDENTIFIER ;
    case  STRING ;
    case  NUMBER ;

    //Keywords -> add all your keywords here
    case  AND ;
    case NULL;
    case  eCLASS ;
    case  ELSE ;
    case  FALSE ;
    case  TRUE ;
    case  FN ;
    case  PUB ;
    case  PRIV ;
    case  FOR ;
    case  IF ;
    case  OR ;
    case  PRINT ;
    case  RETURN ;
    case  THIS ;
    case  LET ;
    case  VAR ;
    case  WHILE ;
    case  FOREACH ;
    case  ELSEIF ;
    case REF; // $, will work like & in rust (possibly)

    //always last one:
    case  EOF ; //End Of File
}

<?php

namespace classes;
use NextPHP\clsMain;
use NextPHP\NPHP;

require_once  "clsTokenType.php";
require_once  "clsToken.php";

class clsScanner
{
    //variables
    private $source;
    private $tokens = [];
    //private $keywords = [];
    private int $start = 0;
    private int $current = 0;
    private int $line = 1;

    const keywords = [
            "class" => clsTokenType::eCLASS,
            "else" => clsTokenType::ELSE,
            "elseif" => clsTokenType::ELSEIF,
            "if" => clsTokenType::IF,
            "while" => clsTokenType::WHILE,
            "for" => clsTokenType::FOR,
            "foreach" => clsTokenType::FOREACH,
            "return" => clsTokenType::RETURN,
            "fn" => clsTokenType::FN,
            "pub" => clsTokenType::PUB,
            "priv" => clsTokenType::PRIV,
            "null" => clsTokenType::NULL,
            "let" => clsTokenType::LET,
            "var" => clsTokenType::VAR,
            "println" => clsTokenType::PRINT,
            "this" => clsTokenType::THIS
        ]; // more direct lookup than going through all cases until x word
//helper functions

//construction of variables
    public function __construct($source) {
        $this->source = $source;
    }

    // functions for adding tokens
    public function addToken($type, $literal = null) {
        if ($literal === null) {
            $literal = $type;
        }
        $token = new clsToken($type, null, $literal, null);
        $this->tokens[] = $token;
    }
    private function addToken2($type, $literal) {
        $text = substr($this->source, $this->start, $this->current - $this->start);
        $token = new clsToken($type, $text, $literal, $this->line);
        $this->tokens[] = $token;
    }
    // functions for reading chars
    public function advance(): string {
        return $this->source[$this->current++];
    }
    private function isAtEnd(): bool {
        return $this->current >= strlen($this->source);
    }

    private function match($expectedChar) {
        if ($this->isAtEnd()) return false;
        if ($this->source[$this->current] != $expectedChar) return false;

        $this->current++;  // Consume the character
        return true;
    }
    public function peek() {
        if ($this->isAtEnd()) {
            return "\0";
        }
        return $this->source[$this->current];
    }
    public function peekNext() {
        if ($this->current + 1 >= strlen($this->source)) {
            return "\0";
        }
        return $this->source[$this->current + 1];
    }
    public function string() {
        while ($this->peek() != '"' && !$this->isAtEnd()) {
            if ($this->peek() == "\n") {
                $this->line++;
                $this->advance();
            }
        }
        if ($this->isAtEnd()) {
            NPHP::error($this->line, "String must be closed");
        }

        $this->advance();

        $value = substr($this->source, $this->start + 1, $this->current - 1);
        $this->addToken(clsTokenType::STRING, $value);
    }
    //functions for numbers:
    public function isDigit($c): bool {
        return $c >= '0' && $c <= '9';
    }

    public function number() {
        while ($this->isDigit($this->peek())) {
            $this->advance();
        }
        if ($this->peek() == "." && $this->isDigit(peekNext())) {
            $this->advance();
            while ($this->isDigit($this->peek())) {
                $this->advance();
            }
        }
        $this->addToken2(clsTokenType::NUMBER, (float) substr($this->source, $this->start, $this->current - $this->start));
    }
    public function isAlpha($c): bool {
        return  ($c >= 'a' && $c <= 'z') || ($c >= 'A' && $c <= 'Z') || $c == '_';
    }
    public function isAlphaNumeric($c): bool {
        return $this->isAlpha($c) || $this->isDigit($c);
    }
    public function identifier() {
        while ($this->isAlphaNumeric($this->peek())) {
            $this->advance();
        }
        $text = substr($this->source, $this->start, $this->current - $this->start);
        $type = $this->scanIsKeyword($text);
        if ($type == null) {
            $type = clsTokenType::IDENTIFIER;
        }
        $this->addToken($type);
    }


    // main functions
    public function scanTokens(): array {
        $line = $this->line;
        while (!$this->isAtEnd()){
            $this->start = $this->current;
            $this->scanToken();
        }
        $token = new clsToken("EOF", "", null, $line);
        $this->tokens[] = $token;
        return $this->tokens;
    }

    // an array works better for keywords, because it is more direct.
    public function scanIsKeyword($s): mixed {
            if (isset(self::keywords[$s])) {
                return self::keywords[$s];
            }
        return null;
    }

    public function scanToken(): void{
        $c = $this->advance(); //the function that goes to the next char every time
        switch ($c) { // the switch where tokens get added
            case "(": $this->addToken(clsTokenType::LEFT_PAREN); break;
            case ")": $this->addToken(clsTokenType::RIGHT_PAREN); break;
            case "{": $this->addToken(clsTokenType::LEFT_BRACE); break;
            case "}": $this->addToken(clsTokenType::RIGHT_BRACE); break;
            case ",": $this->addToken(clsTokenType::COMMA); break;
            case ".": $this->addToken(clsTokenType::DOT); break;
            case "-": $this->addToken(clsTokenType::MINUS); break;
            case "+": $this->addToken(clsTokenType::PLUS); break;
            case ";": $this->addToken(clsTokenType::SEMICOLON); break;
            case "*": $this->addToken(clsTokenType::STAR); break;
            case "%": $this->addToken(clsTokenType::MODULO); break;
            case "$": $this->addToken(clsTokenType::REF); break;
            case "!":
                $this->addToken($this->match("=") ? clsTokenType::BANG_EQUAL : clsTokenType::BANG );
                break;
            case "=":
                $this->addToken($this->match("=") ? clsTokenType::EQUAL_EQUAL : clsTokenType::EQUAL );
                break;
            case "<":
                $this->addToken($this->match("=") ? clsTokenType::LESS_EQUAL : clsTokenType::LESS );
                break;
            case ">":
                $this->addToken($this->match("=") ? clsTokenType::GREATER_EQUAL : clsTokenType::GREATER );
                break;
            case '/':
                if ($this->match('/')) {
                    while ($this->peek() != "\n" && !$this->isAtEnd()) {
                        $this->advance();
                    }
                    $this->addToken(clsTokenType::SLASH);
                } break;
            case "|":
                if ($this->match('|')) {
                    while ($this->peek() != "\n" && !$this->isAtEnd()) {
                        $this->advance();
                    }
                    $this->addToken(clsTokenType::OR);
                }break;
            case "&":
                if ($this->match('&')) {
                    while ($this->peek() != "\n" && !$this->isAtEnd()) {
                        $this->advance();
                    }
                    $this->addToken(clsTokenType::AND);
                }break;
            case ' ': break;
            case '\r': break;
            case '\t': break;
            case '\n':
                $this->line++;
                break;
            case '"': $this->string();
            break;
            default:
                if ($this->isDigit($c)) {
                    $this->number();
                } elseif ($this->isAlpha($c)) {
                    $this->identifier();
                } else {
                    NPHP::error($this->line, "undefined character '" . $c . "' \n ");
                }

                break;
        }
    }

}
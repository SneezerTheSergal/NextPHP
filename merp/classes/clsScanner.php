<?php

namespace classes;
require_once  "clsTokenType.php";
class clsScanner
{
    private $source;
    private $tokens = [];
    private int $start = 0;
    private int $current = 0;
    private int $line = 1;
//helper functions

    public function advance(): string {
        return $this->source[$this->current++];
    }
    public function addToken($type) {
        $token = new clsToken($type);
        $this->tokens[] = $token;
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
    public function string() {
        while ($this->peek() != '"' && !$this->isAtEnd()) {
            if ($this->peek() == "\n") {
                $this->line++;
                $this->advance();
            }
        }
        if ($this->isAtEnd()) {
            clsMain::error($this->line, "String must be closed");
        }

        $this->advance();

        $value = substr($this->source, $this->start + 1, $this->current - 1);
        $this->addToken(clsTokenType::STRING, $value);
    }
    public function __construct($source) {
        $this->$source = $source;
    }

    public function scanTokens($line): array {
        while (!$this->isAtEnd()){
            $this->start = $this->current;
            $this->scanToken();
        }
        $token = new clsToken("EOF", "", null, $this->line);
        $this->tokens[] = $token;
        return $this->tokens;
    }

    public function scanToken(){
        $c = $this->advance();
        switch ($c) {
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
            case ' ': break;
            case '\r': break;
            case '\t': break;
            case '\n':
                $this->line++;
                break;
            case '"': $this->string();
            break;
            default:
                clsMain::error($this->line, "AAAAAAAAAAAA WHAT IS THIS CHARACTER");
                break;
        }
    }

}
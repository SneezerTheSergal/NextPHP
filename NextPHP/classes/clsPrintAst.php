<?php
namespace classes;
use mysql_xdevapi\Expression;
require_once "clsToken.php";

require_once "Expr.php";

class clsPrintAst implements Visitor
{
    public function printLn(Expr $expr): string {
        return $expr->accept($this);
    }

    public function visitBinaryExpr($Binaryexpr)
    {
        return $this->parenthesize($Binaryexpr->operator->lexeme, $Binaryexpr->left, $Binaryexpr->right);
    }
    public function visitGroupingExpr($Groupingexpr)
    {
        return $this->parenthesize("Group", $Groupingexpr);
    }
    public function visitLiteralExpr($Literalexpr): ?string
    {
        if ($Literalexpr == null) {
            return null;
        }
        return  $Literalexpr->toString($Literalexpr);
    }
    public function visitUnaryExpr($Unaryexpr)
    {
        return $this->parenthesize($Unaryexpr->operator->lexeme, $Unaryexpr->right);
    }

    private function parenthesize(mixed $name, Expr ...$exprs): string {
        $builder = "(" . $name;
        foreach ($exprs as $expr) {
            $builder .= " ";
            $builder .= $expr->accept($this);
        }
        $builder .= ")\n";
        return $builder;
    }

    public function main(/*$args*/): void
    {
        $expression = new Binary(
            new Unary(
                new clsToken(clsTokenType::MINUS, "-", null, 1),
                new Literal(123)
            ),
            new clsToken(clsTokenType::STAR, "*", null, 1),
            new Grouping(
                new Literal(45.67)
            )
        );

        echo $this->printLn($expression);
    }

}
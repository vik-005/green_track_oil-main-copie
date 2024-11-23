<?php
namespace App\Doctrine;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\Lexer;

class MonthFunction extends FunctionNode
{
public $dateExpression = null;

public function parse(\Doctrine\ORM\Query\Parser $parser)
{
$parser->match(Lexer::T_IDENTIFIER);
$parser->match(Lexer::T_OPEN_PARENTHESIS);
$this->dateExpression = $parser->ArithmeticPrimary();
$parser->match(Lexer::T_CLOSE_PARENTHESIS);
}

public function getSql(\Doctrine\ORM\Query\SqlWalker $sqlWalker)
{
return 'MONTH(' . $this->dateExpression->dispatch($sqlWalker) . ')';
}
}
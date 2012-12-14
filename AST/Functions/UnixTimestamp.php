<?php

namespace HarvestCloud\CoreBundle\AST\Functions;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;
use Doctrine\ORM\Query\Lexer;

/**
 * UNIX_TIMESTAMP DQL function
 *
 * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
 * @since  2012-12-13
 */
class UnixTimestamp extends FunctionNode
{
    /**
     * @var \Doctrine\ORM\Query\AST\ArithmeticExpression
     */
    private $date;

    /**
     * Parse DQL Function
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-12-13
     *
     * @param Parser $parser
     */
    public function parse(Parser $parser)
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);
        $this->date = $parser->ArithmeticExpression();
        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }

    /**
     * Get SQL
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-12-13
     *
     * @param SqlWalker $sqlWalker
     *
     * @return string
     */
    public function getSql(SqlWalker $sqlWalker)
    {
        return 'UNIX_TIMESTAMP(' .
            $sqlWalker->walkArithmeticExpression($this->date) .
        ')';

    }
}

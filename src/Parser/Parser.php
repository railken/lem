<?php

namespace Railken\Laravel\Manager\Parser;


use PhpParser\V4\Lexer;
use PhpParser\V4\NodeTraverser;
use PhpParser\V4\NodeVisitor;
use PhpParser\V4\NodeVisitorAbstract;
use PhpParser\V4\Parser\Php7;
use PhpParser\V4\PrettyPrinter;

class Parser
{

    /**
     * Parse the code with a visitor.
     *
     * @param string                         $path
     * @param \PhpParser\NodeVisitorAbstract $visitor
     */
    public function edit($path, NodeVisitorAbstract $visitor)
    {
        
        $lexer = new Lexer\Emulative([
            'usedAttributes' => [
                'comments',
                'startLine', 'endLine',
                'startTokenPos', 'endTokenPos',
            ],
        ]);

        $parser = new Php7($lexer, [
            'useIdentifierNodes'         => true,
            'useConsistentVariableNodes' => true,
            'useExpressionStatements'    => true,
            'useNopStatements'           => false,
        ]);

        $traverser = new NodeTraverser();
        $traverser->addVisitor(new NodeVisitor\CloningVisitor());
        $traverser->addVisitor($visitor);

        $printer = new PrettyPrinter\Standard();
        $code = file_get_contents($path);
        $oldStmts = $parser->parse($code);
        $oldTokens = $lexer->getTokens();

        $newStmts = $traverser->traverse($oldStmts);

        $newCode = $printer->printFormatPreserving($newStmts, $oldStmts, $oldTokens);

        file_put_contents($path, $newCode);
    }
}
<?php

namespace Railken\Laravel\Manager\Parser\Visitors;

use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;

class ModelManagerVisitor extends NodeVisitorAbstract
{
    protected $attribute;

    public function __construct($attribute)
    {
        $this->attribute = $attribute;
    }

    public function leaveNode(Node $node)
    {
        if ($node instanceof \PhpParser\Node\Stmt\Property && $node->props[0]->name == 'attributes') {
            $results = array_filter($node->props[0]->default->items, function ($node) {
                return implode("\\", $node->value->class->parts) === implode("\\", $this->attribute);
            });

            print_r($this->attribute);
            if (count($results) < 1) {
                $node->props[0]->default->items[] = new \PhpParser\Node\Expr\ClassConstFetch(
                    new \PhpParser\Node\Name($this->attribute),
                    new \PhpParser\Node\Identifier("class")
                );

                return $node;
            }
        }
    }
}

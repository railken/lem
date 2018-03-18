<?php

namespace Railken\Laravel\Manager\Parser\Visitors;

use PhpParser\V4\Node;
use PhpParser\V4\NodeVisitorAbstract;
use PhpParser\V4\Node\Stmt\Property;
use PhpParser\V4\Node\Expr\ClassConstFetch;
use PhpParser\V4\Node\Name;
use PhpParser\V4\Node\Identifier;

class ModelManagerVisitor extends NodeVisitorAbstract
{
    protected $attribute;

    public function __construct($attribute)
    {
        $this->attribute = $attribute;
    }

    public function leaveNode(Node $node)
    {
        if ($node instanceof Property && $node->props[0]->name == 'attributes') {
            $results = array_filter($node->props[0]->default->items, function ($node) {
                return implode('\\', $node->value->class->parts) === implode('\\', $this->attribute);
            });

            if (count($results) < 1) {
                $node->props[0]->default->items[] = new ClassConstFetch(
                    new Name($this->attribute),
                    new Identifier('class')
                );

                return $node;
            }
        }
    }
}

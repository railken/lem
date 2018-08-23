<?php

namespace Railken\Laravel\Manager\Parser\Visitors;

use PhpParser\Node;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Property;
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
        if ($node instanceof Property && $node->props[0]->name == 'attributes') {
            if (!$node->props[0]->default || !isset($node->props[0]->default->items)) {
                throw new \Exception(sprintf('Cannot retrieve attribute correctly %s', $node->props[0]->name));
            }

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

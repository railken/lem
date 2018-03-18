<?php

namespace Railken\Laravel\Manager\Parser\Visitors;

use PhpParser\V4\Node;
use PhpParser\V4\NodeVisitorAbstract;
use PhpParser\V4\Node\Stmt\Property;
use PhpParser\V4\Node\Scalar\String_;

class ModelVisitor extends NodeVisitorAbstract
{
    protected $attribute;

    public function __construct($attribute)
    {
        $this->attribute = $attribute;
    }

    public function leaveNode(Node $node)
    {
        if ($node instanceof Property && $node->props[0]->name == 'fillable') {
            $results = array_filter($node->props[0]->default->items, function ($node) {
                return $node->value->value === $this->attribute;
            });

            if (count($results) < 1) {
                $node->props[0]->default->items[] = new String_($this->attribute);

                return $node;
            }
        }
    }
}

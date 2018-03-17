<?php

namespace Railken\Laravel\Manager;
use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;

class ModelVisitor extends NodeVisitorAbstract
{	

	protected $attribute;

	public function __construct($attribute)
	{
		$this->attribute = $attribute;
	}

    public function leaveNode(Node $node)
    {
        if ($node instanceof \PhpParser\Node\Stmt\Property && $node->props[0]->name == 'fillable') {
            $node->props[0]->default->items[] = new \PhpParser\Node\Scalar\String_($this->attribute);
        }
    }
}

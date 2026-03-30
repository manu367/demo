<?php
class Add implements Expression {
    private Expression $left;
    private Expression $right;

    public function __construct(Expression $left, Expression $right) {
        $this->left = $left;
        $this->right = $right;
    }

    public function interpret() {
        return $this->left->interpret() + $this->right->interpret();
    }
}

class Subtract implements Expression {
    private Expression $left;
    private Expression $right;

    public function __construct(Expression $left, Expression $right) {
        $this->left = $left;
        $this->right = $right;
    }

    public function interpret() {
        return $this->left->interpret() - $this->right->interpret();
    }
}

$expression = new Subtract(new Add(new Number(5), new Number(3)), new Number(2));
echo $expression->interpret();
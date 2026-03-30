<?php
class Number implements Expression {
    private $value;

    public function __construct($value) {
        $this->value = $value;
    }

    public function interpret() {
        return $this->value;
    }
}
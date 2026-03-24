<?php
class ArrayIteratorCustom implements MyIterator {
    private array $items;
    private int $position = 0;
    public function __construct(array $items) {
        $this->items = $items;
    }
    public function hasNext(): bool {
        return $this->position < count($this->items);
    }
    public function next() {
        return $this->items[$this->position++];
    }
}
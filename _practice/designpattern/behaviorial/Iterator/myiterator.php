<?php
interface MyIterator {
    public function hasNext(): bool;
    public function next();
}
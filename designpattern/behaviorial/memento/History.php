<?php

// caretaker
class History {
    private $mementos = [];

    public function push(Memento $memento) {
        $this->mementos[] = $memento;
    }

    public function pop(){
        return array_pop($this->mementos);
    }
}
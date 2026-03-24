<?php
// organizor
class Editor {
    private string $content;

    public function setContent(string $content) {
        $this->content = $content;
    }

    public function getContent(): string {
        return $this->content;
    }

    public function save() {
        return new Memento($this->content);
    }

    public function restore(Memento $memento) {
        $this->content = $memento->getState();
    }
}
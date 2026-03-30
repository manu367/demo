<?php

$editor = new Editor();
$history = new History();
$editor->setContent("Version 1");
$history->push($editor->save());

$editor->setContent("Version 2");
$history->push($editor->save());

$editor->setContent("Version 3");
echo $editor->getContent(); // Version 3

// Undo
$editor->restore($history->pop());
echo $editor->getContent(); // Version 2
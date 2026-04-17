<?php
namespace _practice\ManuDemo;
class User {
    public $name;
    public function setName($name) {
        $this->name = $name;
    }
    public function getName() {
        return $this->name;
    }
}

$user=new User();
$user->setName("John Doe");
echo $user->getName();
<?php

class RolePermissionObserver implements  SplSubject{
    private $observer=array();
    private $tabid=[];
    public function attach(SplObserver $observer){
        $this->observer[]=$observer;
    }

    public function detach(SplObserver $observer){
        $this->observer = array_filter($this->observer, function ($ob) use ($observer) {
            return $ob !== $observer;
        });
    }

    public function notify()
    {
        foreach ($this->observer as $ob) {
            $ob->update($this);
        }
    }
    public function setTabId($tabid){
        $this->tabid=$tabid;
    }
    public function getTabId(){
        return $this->tabid;
    }
}

class AdminSubScriber implements SplObserver{

    public function update(SplSubject $subject)
    {
        var_dump($subject->getTabId());
    }
}



$role=new RolePermissionObserver();
$admin=new AdminSubScriber();
$role->attach($admin);
$role->setTabId([1,2,3,4,5,6,7,8,9,10]);
$role->notify();
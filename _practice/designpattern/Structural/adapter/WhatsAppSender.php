<?php
namespace DesignPatterns\Structural\adapter;
use SendMessage;

class WhatsAppSender implements SendMessage{
    private $sender;
    public function __construct(SendMessage $value)
    {
        $this->sender = $value;
    }
    public function send($message){
        $this->sender->send($message);
    }
}

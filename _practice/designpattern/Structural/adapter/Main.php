<?php
namespace designpattern\Structural\adapter\WhatsAppSender;
class WhatsApp{
    public function messageSender($message){
        echo "Sender:".$message."\n";
    }
}


$whatsApp = new WhatsApp();
$adapter=new WhatsAppSender($whatsApp);
$a
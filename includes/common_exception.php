<?php
 class GlobalException extends Exception{
     private $msg;
     public function __construct($message,$code = 0, Exception $previous = null){
         $this->msg=$message;
         parent::__construct($message, $code, $previous);
     }
     public function getError() {
         return $this->getMessage();
     }
 }
/**
 *  Global Page , Error Handling from this classes. </br>
 * Uses : throw new FMSException("error","msg","redirect_point_location","pid","hid");
 */
class FMSExceptionHandler extends Exception{
    protected $type;
    protected $location;
    protected $pid,$hid;
    public function __construct($type,
                                $message,
                                $location,$pid,$hid){
        $this->type = $type;
        $this->location = $location;
        $this->pid = $pid;
        $this->hid = $hid;
        parent::__construct($message, 0, null);
    }
    public function location(){
        header("location:$this->location?pid=$this->pid&hid=$this->hid&type=$this->type&msg=$this->message");
        exit();
    }
}
<?php

class Data {
    public $server;
    public $user;
    public $password;
    public $db;

    public function __construct() {
        $hostName = gethostname();
        
        switch ($hostName) {
            case "loren": 
                $this->isActive = false;
                $this->server = "127.0.0.1";
                $this->user = "root";
                $this->password = "1234";
                $this->db = "bdluv";
                break;
            case "admin": 
                $this->isActive = false;
                $this->server = "127.0.0.1";
                $this->user = "root";
                $this->password = "";
                $this->db = "bdluv";
                break;
            default: 
                 $this->isActive = false;
      			 $this->server = "127.0.0.1";
      			 $this->user = "root";
      			 $this->password = "";
      			 $this->db = "bdluv"; 
                break;
        }
    }

}

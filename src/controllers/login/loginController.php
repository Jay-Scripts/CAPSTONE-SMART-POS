<?php

class loginController{
    private $IDNumber = "";


    public function __construct($IDNumber) {
        $this->IDNumber = $IDNumber;
    }


    private function emptyInput(){
        $result = false;

        if(empty($this->IDNumber)){
            $result = false;
        }
        else{
            $result = true;
        }

        return $result;
    }
}
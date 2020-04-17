<?php
class Test
{
    //properties ehk muutujad
    private $secretNum = 3; //st seda et ta on kättesaadav selle klassi piires aga väljaspool teda kasutada ei saa
    public $number = 9;

    function _construct()
    {
        echo "laeti klass!";
        echo "salajane number on: " . $this->secretNum;
        echo "avalik number on: " . $this->number;
    } //konstruktor lõppeb

    function __destruct()
    {
        echo "klass lõpetab";
    }
    public function reveal()
    {
        echo "salajane number on: " . $this->secretNum;
    }
}//class lõppeb

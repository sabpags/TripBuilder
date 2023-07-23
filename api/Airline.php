<?php
class Airline
{
    public $name, $iataCode;

    public function __construct($name, $iataCode)
    {
        $this->name = $name;
        $this->iataCode = $iataCode;
    }
}
?>
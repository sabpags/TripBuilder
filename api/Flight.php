<?php
class Flight
{
    public $airline, $number, $departureAirport, $departureTime, $arrivalAirport, $arrivalTime, $price;

    public function __construct($airline, $number, $departureAirport, $departureTime, $arrivalAirport, $arrivalTime, $price)
    {
        $this->airline = $airline;
        $this->number = $number;
        $this->departureAirport = $departureAirport;
        $this->departureTime = $departureTime;
        $this->arrivalAirport = $arrivalAirport;
        $this->arrivalTime = $arrivalTime;
        $this->price = $price;
    }
}

?>
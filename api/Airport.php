<?php
class Airport
{
    public $name, $code, $cityCode, $city, $countryCode, $regionCode, $latitude, $longitude, $timezone;

    public function __construct($name, $code, $cityCode, $city, $countryCode, $regionCode, $latitude, $longitude, $timezone)
    {
        $this->name = $name;
        $this->code = $code;
        $this->cityCode = $cityCode;
        $this->city = $city;
        $this->countryCode = $countryCode;
        $this->regionCode = $regionCode;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->timezone = $timezone;
    }
}
?>
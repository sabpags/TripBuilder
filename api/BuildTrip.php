<?php
class BuildTrip
{
    public $departureAirport, $arrivalAirport, $departureDate, $arrivalDate, $tripType, $preferredAirline;

    public function __construct($departureAirport, $arrivalAirport, $departureDate, $tripType, $arrivalDate, $preferredAirline = null)
    {
        $this->departureAirport = $departureAirport;
        $this->arrivalAirport = $arrivalAirport;
        $this->departureDate = $departureDate;
        $this->tripType = $tripType;
        $this->arrivalDate = $arrivalDate;
        $this->preferredAirline = $preferredAirline;

    }

    // Function to filter flights by the preferred airline
    function filterFlightsByPreferredAirline($flights)
    {
        $filteredFlights = [];
        foreach ($flights as $flight) {
            if ($flight->airline == $this->preferredAirline) {
                $filteredFlights[] = $flight;
            }
        }
        return $filteredFlights;
    }

    function getAvailableFlights($flights, $departureAirport, $arrivalAirport)
    {
        $availableDepartureFlights = [];
        // Search for available departure flights based on the criteria 
        foreach ($flights as $flight) {
            if (
                $flight->departureAirport == $departureAirport
                && $flight->arrivalAirport == $arrivalAirport
            ) {
                $availableDepartureFlights[] = $flight;
            }
        }
        return $availableDepartureFlights;
    }

    // Calculate price of all available flights (total sum)
    function calculateTotalPrice($availableDepartureFlights, $availableArrivalFlights = [])
    {
        $totalPrice = [];
        if (empty($availableArrivalFlights)) { // if trip-type is one-way -> there is no arrival flight needed
            for ($x = 0; $x < count($availableDepartureFlights); $x++) {
                $totalPrice[$x] = $availableDepartureFlights[$x]->price;
            }
        } else {
            for ($x = 0; $x < count($availableDepartureFlights); $x++) {
                $totalPrice[$x] = ($availableDepartureFlights[$x]->price + $availableArrivalFlights[$x]->price);
            }
        }
        return $totalPrice;
    }

    function formatOutput($availableDepartureFlights, $totalPrices, $availableArrivalFlights = [])
    {
        $trips = [];
        if (empty($availableArrivalFlights)) {
            for ($x = 0; $x < count($availableDepartureFlights); $x++) {
                $trips[] = array(
                    "price" => number_format($totalPrices[$x], 2),
                    "flights" => $availableDepartureFlights[$x]
                );
            }
        } else {
            for ($x = 0; $x < count($availableDepartureFlights); $x++) {
                $trips[] = array(
                    "price" => number_format($totalPrices[$x], 2),
                    "flights" => array(
                        $availableDepartureFlights[$x],
                        $availableArrivalFlights[$x]
                    )
                );
            }
        }
        return $trips;
    }

}
?>
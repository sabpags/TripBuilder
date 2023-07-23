<?php
require_once 'api/Airline.php';
require_once 'api/Airport.php';
require_once 'api/Flight.php';
require_once 'api/BuildTrip.php';

function loadDataFromJson($file)
{
    $data = file_get_contents($file);
    return json_decode($data, true);
}

function loadHTMLForm($airlines, $airports)
{
    echo '<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Trip Builder</title>
    </head>
    <body>
    <h1>Trip Builder</h1>
    <form method="post" action="" id="tripForm">
        <label for="departureAirport">Departure Airport:</label>
        <select id="departureAirport" name="departure_airport" required> ';

    foreach ($airports as $airport) {
        echo '<option value="' . $airport->code . '">' . $airport->name . ' (' . $airport->code . ')</option>';
    }
    echo '
    </select><br>

    <label for="arrivalAirport">Arrival Airport:</label>
    <select id="arrivalAirport" name="arrival_airport" required> ';

    foreach ($airports as $airport) {
        echo '<option value="' . $airport->code . '">' . $airport->name . ' (' . $airport->code . ')</option>';
    }
    echo '
    </select><br>

    <label for="departureDate">Departure Date:</label>
    <input type="date" id="departureDate" name="departure_date" required><br>

    <label for="returnDate">Return Date:</label>
    <input type="date" id="returnDate" name="return_date"><br>

    <label for="tripType">Trip Type:</label>
    <select id="tripType" name="trip_type" required>
        <option value="one-way">One-way</option>
        <option value="round-trip">Round-trip</option>
    </select><br>

    <label for="preferredAirline">Preferred Airline:</label>
    <select id="preferredAirline" name="preferred_airline"> 
    <option value=""></option>
    ';
    foreach ($airlines as $airline) {
        echo '<option value="' . $airline->iataCode . '">' . $airline->name . ' (' . $airline->iataCode . ')</option>';
    }
    echo '
    </select><br>

    <button type="submit" name="submit">Build Trip</button>
    </form>
    </body>
    </html>
';
}

 /* Search for available arrival flights based on the criteria (if trip_type is round-trip / one-way)
        Calculate total sum of each 'trip' and format the output of the search
    */
function getResponse($flights, $tripBuilder)
{
    if($tripBuilder->preferredAirline != null){
        $flights  = $tripBuilder->filterFlightsByPreferredAirline($flights);
    }
    $availableDepartureFlights = $tripBuilder->getAvailableFlights($flights,$tripBuilder->departureAirport, $tripBuilder->arrivalAirport);
    if ($tripBuilder->tripType == 'round-trip') {
        $availableArrivalFlights = $tripBuilder->getAvailableFlights($flights, $tripBuilder->arrivalAirport, $tripBuilder->departureAirport);
        $totalPrices = $tripBuilder->calculateTotalPrice($availableDepartureFlights, $availableArrivalFlights);
        $trips = $tripBuilder->formatOutput($availableDepartureFlights, $totalPrices, $availableArrivalFlights);

    } else {
        $totalPrices = $tripBuilder->calculateTotalPrice($availableDepartureFlights);
        $trips = $tripBuilder->formatOutput($availableDepartureFlights, $totalPrices);
    }

    $output = json_encode(array('response' => array('trips' => $trips)));
    echo '<h2>JSON Response:</h2>';
    echo '<pre>' . $output . '</pre>';
}
// Load data from data.json file
$data = loadDataFromJson('data.json');

// Initialize arrays to hold airlines, airports, and flights
$airlines = [];
$airports = [];
$flights = [];

// Load airline data
foreach ($data['airlines'] as $airlineData) {
    $airlines[$airlineData['code']] = new Airline($airlineData['name'], $airlineData['code']);
}

// Load airport data
foreach ($data['airports'] as $airportData) {
    $airports[$airportData['code']] = new Airport(
        $airportData['name'],
        $airportData['code'],
        $airportData['city_code'],
        $airportData['city'],
        $airportData['country_code'],
        $airportData['region_code'],
        $airportData['latitude'],
        $airportData['longitude'],
        $airportData['timezone']
    );
}

// Load flight data
foreach ($data['flights'] as $flightData) {
    $flight = new Flight(
        $flightData['airline'],
        $flightData['number'],
        $flightData['departure_airport'],
        $flightData['departure_time'],
        $flightData['arrival_airport'],
        $flightData['arrival_time'],
        $flightData['price']
    );
    $flights[] = $flight;
}

loadHTMLForm($airlines, $airports);

if (isset($_POST['submit'])) {

    // Build a trip
    $tripBuilder = new BuildTrip(
        $_POST['departure_airport'],
        $_POST['arrival_airport'],
        $_POST['departure_date'],
        $_POST['trip_type'],
        $_POST['return_date'],
        $_POST['preferred_airline']
    );

    // Initilize all varibles needed
    $availableDepartureFlights = [];
    $availableArrivalFlights = [];
    $totalPrices = [];
    $trips = [];

    // Check if return date is not before departure date for round-trip
    if ($tripBuilder->tripType === 'round-trip') {
        $departureDate = strtotime($tripBuilder->departureDate);
        $returnDate = strtotime($tripBuilder->arrivalDate);

        if ($returnDate < $departureDate) {
            echo '<p style="color: red;">Error: Return date cannot be before departure date for round-trip.</p>';
        } else {
            $requestData = [
                'request' => $tripBuilder
            ];

            $jsonData = json_encode($requestData);
            echo '<h2>JSON Request:</h2>';
            echo '<pre>' . $jsonData . '</pre>';
            getResponse($flights, $tripBuilder);
        }
    } else {
        // For one-way trips
        $requestData = [
            'request' => $tripBuilder
        ];

        $jsonData = json_encode($requestData);
        echo '<h2>JSON Request:</h2>';
        echo '<pre>' . $jsonData . '</pre>';
        getResponse($flights, $tripBuilder);
    }
}
?>
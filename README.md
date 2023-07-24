# TripBuilder

Instructions to install and run program

1. Download and install XAMPP to host PHP program
2. Open XAMPP application and start the 'Apache Web Server'
3. Open terminal and navigate to the '/htdocs' folder that is located under the XAMPP folder on your local drive
4. Type ' git clone https://github.com/sabpags/TripBuilder.git '
5. This will place our PHP program files directly from the main branch from its github repository
6. Once the files are placed, open your web browser and enter "localhost/TripBuilder/index.php" to begin the program

# Trip Builder API Documentation

Trip Builder API allows you to build and navigate trips for a single passenger using criteria such as departure airport, departure dates, and arrival airport. It supports one-way and round-trip types, and it is written in PHP.

1. BuildTrip

Description: Builds trips based on the provided criteria and returns the available trip options.

Request Parameters:
* departure_airport (string): IATA code of the departure airport.
* arrival_airport (string): IATA code of the arrival airport.
* departure_date (string, format: "YYYY-MM-DD"): Date of departure.
* return_date (string, format: "YYYY-MM-DD"): Date of return (for round-trip type). Optional.
* trip_type (string, "one-way" or "round-trip"): Type of trip.
* preferred_airline (string): Allow users to restrict search to selected airline only. Optional.

Response:

* trips (array): An array of available trips.
    * price (string): The total price of the trip in the neutral currency.
    * flights (array): An array of flight details for each leg of the trip.
        * airline (string): Airline code.
        * number (string): Flight number.
        * departure_airport (string): IATA code of the departure airport.
        * departure_datetime (string, format: "YYYY-MM-DD HH:mm"): Departure date and time (in the local timezone of the departure airport).
        * arrival_airport (string): IATA code of the arrival airport.
        * arrival_datetime (string, format: "YYYY-MM-DD HH:mm"): Arrival date and time (in the local timezone of the arrival airport).
        * price (string): The price of the flight in the neutral currency.


Sample Request

{"request":{"departureAirport":"YUL","arrivalAirport":"YVR","departureDate":"2023-07-25","arrivalDate":"2023-08-08","tripType":"round-trip","preferredAirline":""}}

Sample Response

{"response":{"trips":[{"price":"493.86","flights":[{"airline":"AC","number":"301","departureAirport":"YUL","departureTime":"07:35","arrivalAirport":"YVR","arrivalTime":"10:05","price":"273.23"},{"airline":"AC","number":"302","departureAirport":"YVR","departureTime":"11:30","arrivalAirport":"YUL","arrivalTime":"19:11","price":"220.63"}]}]}}

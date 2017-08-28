<?php
/**
 * Created by PhpStorm.
 * User: maimob
 * Date: 2017/8/28
 * Time: 下午2:44
 */
//规范
//Traveling to Warm Destinations
class Traveler
{
    public $min_temp;
}

class Destination
{
    protected $avg_temps;

    public function __construct($avg_temps)
    {
        $this->avg_temps = $avg_temps;
    }

    public function getAvgTempByMonth($month)
    {
        $key = (int)$month - 1;
        if(array_key_exists($key, $this->avg_temps)) {
            return $this->avg_temps[$key];
        }
    }
}

class Trip
{
    public $date;
    public $traveler;
    public $destination;
}

class TripRequiredTemperatureSpecification
{
    public function isSatisfiedBy($trip)
    {
        $trip_temp = $trip->destination->getAvgTempByMonth(date('m', $trip->date));
        return ($trip_temp >= $trip->traveler->min_temp);
    }
}

class DestinationRequiredTemperatureSpecification
{
    protected $temp;
    protected $month;

    public function __construct($traveler, $date)
    {
        $this->temp = $traveler->min_temp;
        $this->month = $date('m', $date);
    }

    public function isSatisfiedBy($destination)
    {
        return $destination->getAvgTempByMonth($this->month) >= $this->temp;
    }
}

class TripSpecificationTestCase extends PHPUnit_Framework_TestCase
{
    protected $destinations = [];

    protected function setUp()
    {
        $this->destinations = [
            'Toronto'   => new Destination([
                24, 25, 33, 43, 54, 63, 69, 69, 61, 50, 41, 29
            ]),
            'Cancun'    => new Destination([
                74, 75, 78, 80, 82, 84, 84, 84, 83, 81, 78, 76
            ])
        ];
    }

    public function testTripToolGold()
    {
        $vicki = new Traveler();
        $vicki->min_temp = 70;
        $toronto = $this->destinations['Toronto'];

        $trip = new Trip();
        $trip->traveler = $vicki;
        $trip->destination = $toronto;
        $trip->date = mktime(0, 0, 0, 2, 11, 2017);

        $warm_enough_check = new TripRequiredTemperatureSpecification();
        $this->assertFalse($warm_enough_check->isSatisfiedBy($trip));
    }

    public function testFindingDestinations()
    {
        $this->assertEquals(2, count($this->destinations));
        $validDestinations = [];
        $vicki = new Traveler();
        $vicki->min_temp = 70;
        $travelDate = mktime(0, 0, 0, 2, 11, 2017);
//        echo "\n" . $travelDate;
        $warm_enough = new DestinationRequiredTemperatureSpecification($vicki, $travelDate);
//        foreach ($this->destinations as $dest) {
//            if($warm_enough->isSatisfiedBy($dest)) {
//                $validDestinations[] = $dest;
//            }
//        }
//        $this->assertEquals(1, count($validDestinations));
    }
}


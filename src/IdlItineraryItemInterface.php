<?php

namespace Tramsak\IdlCrossing;

/**
 * Itineraries consist of a list of items (e.g. Day1: visit Berlin, Day1: have lunch, Day1: go to hotel, Day2: drive to Munich)
 *
 * As the example shows, multiple itinerary items can be set to the same day/date.
 */
interface IdlItineraryItemInterface
{
    /*
     * IDL constants
     *
     * NO            ... normal item
     * EASTBOUND     ... item represents IDL crossing Eastbound, gaining a day. The item however is just a marker, it does
     *                   not take any time (e.g. Day3: IDL crossing, Day3: watching sea-turtles mate).
     * EASTBOUND_DAY ... item represents IDL crossing Eastbound, gaining a day. The item represents a day of activity
     *                   and will count as a day as well (e.g. Day3: Crossing IDL and watching sea-turtles mate).
     * WESTBOUND     ... item represents IDL crossing Westbound, loosing a day. The item however is just a marker, it does
     *                   not take any time (e.g. Day3: IDL crossing, Day3: watching sea-turtles mate).
     * WESTBOUND_DAY ... item represents IDL crossing Westbound, loosing a day. The item represents a day of activity
     *                   and will count as a day as well (e.g. Day3: Crossing IDL and watching sea-turtles mate).
     */
    const IDL_NO = 0;
    const IDL_EASTBOUND = 1;
    const IDL_EASTBOUND_DAY = 2;
    const IDL_WESTBOUND = 3;
    const IDL_WESTBOUND_DAY = 4;

    /**
     * @return mixed
     */
    public function getDay();

    /**
     * @param mixed $day
     */
    public function setDay($day);

    /**
     * @return \DateTime
     */
    public function getDate();

    /**
     * @param $date
     */
    public function setDate($date);

    /**
     * @return int IDL constant
     */
    public function getIdlSetting();

    /**
     * @param int $idlSetting IDL constant
     */
    public function setIdlSetting($idlSetting);

}
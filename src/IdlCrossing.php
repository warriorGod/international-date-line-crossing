<?php

namespace Tramsak\IdlCrossing;

use Tramsak\NumberRangeString\NumberRangeString;

class IdlCrossing
{

    /**
     * Adjust the day and the date on the itinerary items based on IDL settings
     *
     * @param IdlItineraryItemInterface[] $itineraryList
     */
    public function processItineraryList($itineraryList)
    {
        $prevItem = null;

        $adjustDay = 0;
        $adjustDate = 0;

        foreach ($itineraryList as $itiItem) {

            $idlSetting = $itiItem->getIdlSetting();
            if ($idlSetting) {
                $day = $this->dayToRange($prevItem->getDay());
                if ($idlSetting == IdlItineraryItemInterface::IDL_EASTBOUND) {
                    // gain a day
                    $itiItem->setDay($day->toString());
                    $itiItem->setDate($prevItem->getDate());

                    $next = $this->peakNext($itineraryList, $itiItem);
                    $adjustDay = $this->calculateAdjustDay($this->addToDay($day)->getTo(), $next->getDay());
                    $adjustDate = $this->calculateAdjustDate($prevItem->getDate(), $next->getDate(), $adjustDate);

                } elseif ($idlSetting == IdlItineraryItemInterface::IDL_EASTBOUND_DAY) {
                    // gain a day
                    $setDate = $this->addDayToDate($prevItem->getDate(), 1);
                    $itiItem->setDay($this->addToDay($day)->toString());
                    $itiItem->setDate($setDate);

                    $next = $this->peakNext($itineraryList, $itiItem);
                    $adjustDay = $this->calculateAdjustDay($this->addToDay($day, 2)->getTo(), $next->getDay());
                    $adjustDate = $this->calculateAdjustDate($setDate, $next->getDate(), $adjustDate);
                } elseif ($idlSetting == IdlItineraryItemInterface::IDL_WESTBOUND) {
                    // loose a day
                    $itiItem->setDay($day->toString());
                    $itiItem->setDate($prevItem->getDate());

                    $next = $this->peakNext($itineraryList, $itiItem);
                    $adjustDay = $this->calculateAdjustDay($this->addToDay($day)->getTo(), $next->getDay());
                    $adjustDate = $this->calculateAdjustDate($this->addDayToDate($prevItem->getDate(), 2), $next->getDate(), $adjustDate);

                } elseif ($idlSetting == IdlItineraryItemInterface::IDL_WESTBOUND_DAY) {
                    // loose a day
                    $setDate = $this->addDayToDate($prevItem->getDate(), 1);
                    $itiItem->setDay($this->addToDay($day)->toString());
                    $itiItem->setDate($setDate);

                    $next = $this->peakNext($itineraryList, $itiItem);
                    $adjustDay = $this->calculateAdjustDay($this->addToDay($day, 2)->getTo(), $next->getDay());
                    $adjustDate = $this->calculateAdjustDate($this->addDayToDate($setDate, 2), $next->getDate(), $adjustDate);
                }
            } else {
                $prevItem = $itiItem;
                $day = $this->dayToRange($prevItem->getDay());
                if ($adjustDay) {
                    $itiItem->setDay($day->moveBy($adjustDay)->toString());
                }
                if ($adjustDate) {
                    $itiItem->setDate($this->addDayToDate($itiItem->getDate(), $adjustDate));
                }
            }
        }
    }

    /**
     * @param mixed $day
     * @return NumberRangeString
     */
    protected function dayToRange($day)
    {
        return new NumberRangeString($day);
    }

    /**
     * @param mixed $day
     * @param int   $number
     * @return NumberRangeString|int
     */
    protected function addToDay($day, $number = 1)
    {
        if ($day instanceof NumberRangeString) {
            $newDay = clone $day;
            return $newDay->moveBy($number);
        } else {
            return $day + $number;
        }
    }

    /**
     * @param \DateTime $date
     * @param int       $day
     * @return \DateTime
     */
    protected function addDayToDate($date, $day)
    {
        $return = clone $date;
        if ($day > 0) {
            $return->add(new \DateInterval('P'.$day.'D'));
        } else {
            $return->sub(new \DateInterval('P'.abs($day).'D'));
        }

        return $return;
    }

    /**
     * @param int       $desired
     * @param string    $current Day or day range
     * @return int
     */
    protected function calculateAdjustDay($desired, $current)
    {
        $c = new NumberRangeString($current);

        return $desired - $c->getFrom();
    }

    /**
     * @param \DateTime $desired
     * @param \DateTime $current
     * @param int       $currentAdjustment
     * @return int
     */
    protected function calculateAdjustDate($desired, $current, $currentAdjustment)
    {
        $target = clone $desired;
        $source = $this->addDayToDate($current, $currentAdjustment);

        $diff = $source->diff($target);

        return $diff->invert ? 0 - $diff->days : $diff->days;
    }

    /**
     * Find the next item on the list.
     * @param IdlItineraryItemInterface[] $itineraryList
     * @param IdlItineraryItemInterface   $currentItem
     * @return IdlItineraryItemInterface
     */
    protected function peakNext($itineraryList, $currentItem)
    {
        if (key($itineraryList) === 0) {
            // numerical indexes
            $currentKey = array_search($currentItem, $itineraryList);
            return isset($itineraryList[$currentKey+1]) ? $itineraryList[$currentKey+1] : null;
        } else {
            $return = false;
            foreach ($itineraryList as $item) {
                if ($item === $currentItem) {
                    $return = true;
                    continue;
                }
                if ($return) {
                    return $item;
                }
            }
        }
    }

}
<?php

namespace Tramsak\IdlCrossing\Tests;

use Tramsak\IdlCrossing\IdlCrossing;
use Tramsak\IdlCrossing\IdlItineraryItemInterface;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\BufferedOutput;

class ItineraryItem implements IdlItineraryItemInterface
{
    protected $day;
    protected $date;
    protected $idlSetting;

    /**
     * ItineraryItem constructor.
     * @param $day
     * @param $date
     * @param $idlSetting
     */
    public function __construct($day, $date, $idlSetting = null)
    {
        $this->day = $day;
        $this->date = $date;
        $this->idlSetting = $idlSetting;
    }

    /**
     * @return mixed
     */
    public function getDay()
    {
        return $this->day;
    }

    /**
     * @param mixed $day
     */
    public function setDay($day)
    {
        $this->day = $day;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @return mixed
     */
    public function getIdlSetting()
    {
        return $this->idlSetting;
    }

    /**
     * @param mixed $idlSetting
     */
    public function setIdlSetting($idlSetting)
    {
        $this->idlSetting = $idlSetting;
    }

}

class IdlCrossingTest extends \PHPUnit_Framework_TestCase
{

    protected function getSampleData() {
        return [
            new ItineraryItem(1, new \DateTime('2016-12-24'))
            , new ItineraryItem(2, new \DateTime('2016-12-25'))
            , new ItineraryItem(3, new \DateTime('2016-12-26'))
            , new ItineraryItem(4, new \DateTime('2016-12-27'))
            , new ItineraryItem(5, new \DateTime('2016-12-28'))
        ];
    }


    public function testEastbound()
    {
        $list = $this->getSampleData();
        $list[2]->setIdlSetting(IdlItineraryItemInterface::IDL_EASTBOUND);

        $pass = new IdlCrossing();

        $pass->processItineraryList($list);

        $this->assertEquals(2, $list[2]->getDay());
        $this->assertEquals(3, $list[3]->getDay());
        $this->assertEquals(new \DateTime('2016-12-25'), $list[2]->getDate());
        $this->assertEquals(new \DateTime('2016-12-25'), $list[3]->getDate());
        $this->drawExample($list);
    }

    public function testEastboundAsDay()
    {
        $list = $this->getSampleData();
        $list[2]->setIdlSetting(IdlItineraryItemInterface::IDL_EASTBOUND_DAY);


        $pass = new IdlCrossing();

        $pass->processItineraryList($list);

        $this->assertEquals(3, $list[2]->getDay());
        $this->assertEquals(4, $list[3]->getDay());
        $this->assertEquals(new \DateTime('2016-12-26'), $list[2]->getDate());
        $this->assertEquals(new \DateTime('2016-12-26'), $list[3]->getDate());
        $this->drawExample($list);
    }

    public function testWestbound()
    {
        $list = $this->getSampleData();
        $list[2]->setIdlSetting(IdlItineraryItemInterface::IDL_WESTBOUND);

        $pass = new IdlCrossing();

        $pass->processItineraryList($list);

        $this->assertEquals(2, $list[2]->getDay());
        $this->assertEquals(3, $list[3]->getDay());
        $this->assertEquals(new \DateTime('2016-12-25'), $list[2]->getDate());
        $this->assertEquals(new \DateTime('2016-12-27'), $list[3]->getDate());
        $this->drawExample($list);
    }

    public function testWestboundAsDay()
    {
        $list = $this->getSampleData();
        $list[2]->setIdlSetting(IdlItineraryItemInterface::IDL_WESTBOUND_DAY);

        $pass = new IdlCrossing();

        $pass->processItineraryList($list);

        $this->assertEquals(3, $list[2]->getDay());
        $this->assertEquals(4, $list[3]->getDay());
        $this->assertEquals(new \DateTime('2016-12-26'), $list[2]->getDate());
        $this->assertEquals(new \DateTime('2016-12-28'), $list[3]->getDate());
        $this->drawExample($list);
    }

    protected function drawExample($list)
    {
        $rows = [];
        foreach ($list as $index => $item) {
            $rows[] = [
                $index
                , $item->getDay()
                , $item->getDate()->format('Y-m-d')
                , $item->getIdlSetting()
            ];
        }
        $output = new BufferedOutput();
        $table = new Table($output);
        $table
            ->setHeaders(array('Index', 'Day', 'Date', 'IDL'))
            ->setRows($rows)
        ;
        $table->render();

        echo "\n" . $output->fetch();
    }



}

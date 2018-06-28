<?php


namespace SavvyWombat\Ahora\Tests;

use PHPUnit\Framework\TestCase;
use SavvyWombat\Ahora\Interval;

class IntervalTest extends TestCase
{
    /**
     * @test
     * @covers \SavvyWombat\Ahora\Interval::addSeconds
     * @uses \SavvyWombat\Ahora\Interval::getSeconds
     * @uses \SavvyWombat\Ahora\Interval::__get
     */
    public function adding_seconds()
    {
        $interval = new Interval();

        $this->assertEquals(
            0,
            $interval->seconds,
            'default interval should have 0 seconds'
        );


        $interval->addSeconds(5);

        $this->assertEquals(
            5,
            $interval->seconds,
            'interval should have added 5 seconds'
        );
    }



    /**
     * @test
     * @covers \SavvyWombat\Ahora\Interval::getSeconds
     * @uses \SavvyWombat\Ahora\Interval::addSeconds
     * @uses \SavvyWombat\Ahora\Interval::__get
     */
    public function only_return_remaining_seconds_since_last_minute()
    {
        $interval = new Interval();

        $interval->addSeconds(150);

        $this->assertEquals(
            30,
            $interval->seconds,
            'interval should only return 30 seconds'
        );
    }



    /**
     * @test
     * @covers \SavvyWombat\Ahora\Interval::getRealSeconds
     * @uses \SavvyWombat\Ahora\Interval::addSeconds
     * @uses \SavvyWombat\Ahora\Interval::__get
     */
    public function return_all_the_seconds()
    {
        $interval = new Interval();

        $interval->addSeconds(150);

        $this->assertEquals(
            150,
            $interval->realSeconds,
            'interval should only return 150 seconds'
        );
    }



    /**
     * @test
     * @covers \SavvyWombat\Ahora\Interval::getMinutes
     * @uses \SavvyWombat\Ahora\Interval::addSeconds
     * @uses \SavvyWombat\Ahora\Interval::__get
     */
    public function calculates_minutes_from_seconds()
    {
        $interval = new Interval();

        $interval->addSeconds(150);

        $this->assertEquals(
            2,
            $interval->minutes,
            'interval should return 2 minutes for 150 seconds'
        );
    }
}
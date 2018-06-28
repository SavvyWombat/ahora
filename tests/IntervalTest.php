<?php


namespace SavvyWombat\Ahora\Tests;

use PHPUnit\Framework\TestCase;
use SavvyWombat\Ahora\Interval;

class IntervalTest extends TestCase
{
    /**
     * @test
     * @covers \SavvyWombat\Ahora\Interval::addSeconds
     * @uses \SavvyWombat\Ahora\Interval
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
     * @uses \SavvyWombat\Ahora\Interval
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
     * @uses \SavvyWombat\Ahora\Interval
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
     * @uses \SavvyWombat\Ahora\Interval
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



    /**
     * @test
     * @covers \SavvyWombat\Ahora\Interval::add
     * @uses \SavvyWombat\Ahora\Interval::getMinutes
     * @uses \SavvyWombat\Ahora\Interval::getRealSeconds
     * @uses \SavvyWombat\Ahora\Interval
     */
    public function adding_minutes()
    {
        $interval = new Interval();

        $interval->addMinutes(10);

        $this->assertEquals(
            10,
            $interval->minutes,
            'interval should return 10 minutes'
        );

        $this->assertEquals(
            600,
            $interval->realSeconds,
            'interval should return 600 real seconds'
        );
    }



    /**
     * @test
     * @covers \SavvyWombat\Ahora\Interval::getHours
     * @covers \SavvyWombat\Ahora\Interval::getMinutes
     * @covers \SavvyWombat\Ahora\Interval::getRealMinutes
     * @uses \SavvyWombat\Ahora\Interval
     */
    public function minutes_become_hours()
    {
        $interval = new Interval();

        $interval->addMinutes(225);

        $this->assertEquals(
            3,
            $interval->hours,
            '225 minutes is over 3 hours'
        );

        $this->assertEquals(
            45,
            $interval->minutes,
            '225 minutes is 45 minutes past 3 hours'
        );

        $this->assertEquals(
            225,
            $interval->realMinutes,
            'we added 225 minutes'
        );
    }



    /**
     * @test
     * @covers \SavvyWombat\Ahora\Interval::getDays
     * @covers \SavvyWombat\Ahora\Interval::getHours
     * @covers \SavvyWombat\Ahora\Interval::getRealHours
     * @uses \SavvyWombat\Ahora\Interval
     */
    public function hours_become_days()
    {
        $interval = new Interval();

        $interval->addHours(100);

        $this->assertEquals(
            100,
            $interval->realHours,
            'we added 100 hours'
        );

        $this->assertEquals(
            4,
            $interval->days,
            '100 hours is over 4 days'
        );

        $this->assertEquals(
            4,
            $interval->hours,
            '100 hours is 4 hours past 4 days'
        );
    }
}
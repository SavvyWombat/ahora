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
     * @covers \SavvyWombat\Ahora\Interval::getReal
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
     * @covers \SavvyWombat\Ahora\Interval::get
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
     * @covers \SavvyWombat\Ahora\Interval::get
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
     * @covers \SavvyWombat\Ahora\Interval::get
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



    /**
     * @test
     * @covers \SavvyWombat\Ahora\Interval::setFactors
     * @uses \SavvyWombat\Ahora\Interval
     */
    public function set_factors()
    {
        $interval = new Interval();


        $interval->setFactors([
            'microts' => [1, 'seconds'],
            'cycles' => [86400, 'microts'],
            'yarns' => [365, 'days'],
        ]);

        $interval->addMicrots(100000);

        $this->assertEquals(
            1,
            $interval->cycles,
            '100000 microts > 1 cycle'
        );
    }


    /**
     * @test
     * @covers \SavvyWombat\Ahora\Interval::setFactor
     * @uses \SavvyWombat\Ahora\Interval
     */
    public function eight_hour_days()
    {
        $interval = new Interval();

        $interval->setFactor('days', [8, 'hours']);

        $interval->addHours(25);

        $this->assertEquals(
            3,
            $interval->days,
            '25 hours is 3 eight-hour days plus change'
        );

        $this->assertEquals(
            1,
            $interval->hours,
            '25 hours leaves an extra hour after 3 eight-hour days'
        );
    }



    /**
     * @test
     * @covers \SavvyWombat\Ahora\Interval::getFactors
     * @uses \SavvyWombat\Ahora\Interval
     */
    public function get_factors()
    {
        $interval = new Interval();

        $this->assertEquals(
            [
                'minutes' => [60, 'seconds'],
                'hours' => [60, 'minutes'],
                'days' => [24, 'hours'],
            ],
            $interval->getFactors()
        );

        $interval->setFactor('days', [8, 'hours']);

        $this->assertEquals(
            [
                'minutes' => [60, 'seconds'],
                'hours' => [60, 'minutes'],
                'days' => [8, 'hours'],
            ],
            $interval->getFactors()
        );
    }



    /**
     * @test
     * @covers \SavvyWombat\Ahora\Interval::getFactor
     * @uses \SavvyWombat\Ahora\Interval
     */
    public function get_factor_for_days()
    {
        $interval = new Interval();

        $interval->setFactor('days', [8, 'hours']);

        $this->assertEquals(
            [8, 'hours'],
            $interval->getFactor('days')
        );
    }



    /**
     * @test
     * @uses \SavvyWombat\Ahora\Interval
     */
    public function get_weeks()
    {
        $interval = new Interval();

        $interval->setFactor('weeks', [7, 'days']);

        $interval->addDays(365);

        $this->assertEquals(
            52,
            $interval->weeks
        );

        $this->assertEquals(
            1,
            $interval->days
        );
    }



    /**
     * @test
     * @covers \SavvyWombat\Ahora\Interval::addInterval
     * @uses \SavvyWombat\Ahora\Interval
     */
    public function add_another_interval()
    {
        $interval = new Interval();
        $interval->addSeconds(30);


        $otherInterval = new Interval();
        $otherInterval->addSeconds(70);


        $interval->addInterval($otherInterval);

        $this->assertEquals(
            100,
            $interval->realSeconds
        );
    }



    /**
     * @test
     * @covers \SavvyWombat\Ahora\Interval::createFromDateInterval
     * @uses \DateInterval
     * @uses \SavvyWombat\Ahora\Interval
     */
    public function create_from_date_interval()
    {
        $dateInterval = new \DateInterval("P2DT3H4M5S");

        $interval = Interval::createFromDateInterval($dateInterval);

        $this->assertInstanceOf(
            Interval::class,
            $interval,
            'Interval not returned from create'
        );

        $this->assertEquals(
            2,
            $interval->days,
            'incorrect number of days set'
        );

        $this->assertEquals(
            3,
            $interval->hours,
            'incorrect number of hours set'
        );

        $this->assertEquals(
            4,
            $interval->minutes,
            'incorrect number of minutes set'
        );

        $this->assertEquals(
            5,
            $interval->seconds,
            'incorrect number of seconds set'
        );
    }



    /**
     * @test
     * @covers \SavvyWombat\Ahora\Interval::getIntervalSpec
     * @uses \SavvyWombat\Ahora\Interval
     */
    public function outputs_interval_spec()
    {
        $interval = new Interval();
        $interval->addSeconds(200000); // 2 days, 5 hours, 26 minutes, 40 seconds

        $this->assertEquals(
            "P2DT7H33M20S",
            $interval->intervalSpec
        );
    }
}
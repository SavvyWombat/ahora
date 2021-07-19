<?php
declare(strict_types=1);

namespace SavvyWombat\Ahora;

use DateInterval;

/**
 * Class Interval
 * @package SavvyWombat\Ahora
 *
 * Time interval that allows simple addition/substraction for summing up and presenting
 */
class Interval
{
    /**
     * @var int The number of seconds in this interval
     */
    protected $realSeconds = 0;


    /**
     * @var array Multiplication factors used to get time values from the interval
     *
     * Defaults to 1 day = 24 hours, 1 hour = 60 minutes, 1 minute = 60 seconds
     */
    protected $factors = [
        'minutes' => [60, 'seconds'],
        'hours' => [60, 'minutes'],
        'days' => [24, 'hours'],
    ];


    /**
     * Create an interval from a PHP DateInterval
     *
     * This method currently ignores any unit of time greater than a day
     *
     * @param DateInterval $dateInterval
     * @return Interval
     * @throws \Exception
     */
    public static function createFromDateInterval(DateInterval $dateInterval)
    {
        $interval = new Interval();
        $interval->addDays($dateInterval->d);
        $interval->addHours($dateInterval->h);
        $interval->addMinutes($dateInterval->i);
        $interval->addSeconds($dateInterval->s);

        return $interval;
    }


    /**
     * Create an Interval using an ISO8601 interval specification
     *
     * @param string $interval_spec
     * @return Interval
     * @throws \Exception
     */
    public function createFromIntervalSpec(string $interval_spec)
    {
        return self::createFromDateInterval(new DateInterval($interval_spec));
    }


    /**
     * @return DateInterval
     * @throws \Exception
     */
    public function getAsDateInterval()
    {
        return new DateInterval($this->getIntervalSpec());
    }


    /**
     * Get the interval specification (ISO8601)
     *
     * This method currently ignores any unit of time greater than a day
     *
     * @return string
     * @throws \Exception
     */
    public function getIntervalSpec()
    {
        $spec = "P";

        if ($this->get('days') > 0) {
            $spec .= $this->get('days') . "D";
        }

        $spec .= "T";

        if ($this->get('hours') > 0) {
            $spec .= $this->get('hours') . "H";
        }

        if ($this->get('minutes') > 0) {
            $spec .= $this->get('minutes') . "M";
        }

        // always return at least 'PTOS'
        $spec .= $this->get('seconds') . "S";

        return $spec;
    }


    /**
     * Add another interval to this one
     *
     * @param Interval $otherInterval
     * @return Interval
     */
    public function addInterval(Interval $otherInterval)
    {
        $this->realSeconds += $otherInterval->realSeconds;

        return $this;
    }

    /**
     * Subtract another interval from this one
     *
     * @param Interval $otherInterval
     * @return Interval
     */
    public function subInterval(Interval $otherInterval)
    {
        $this->realSeconds -= $otherInterval->realSeconds;

        return $this;
    }


    /**
     * @param int $seconds
     * @return Interval
     */
    public function addSeconds(int $seconds)
    {
        $this->realSeconds += $seconds;

        return $this;
    }

    /**
     * @param int $seconds
     * @return Interval
     */
    public function subSeconds(int $seconds)
    {
        $this->realSeconds -= $seconds;

        return $this;
    }

    /**
     * Add the specified amount of time to the interval (uses the __call method to allow addHours, for example)
     *
     * @param string $unit Any valid unit from the factors array, including the seconds array
     * @param int $value The number of units to add to the interval
     * @throws \Exception
     */
    protected function add(string $unit, int $value)
    {
        if ($unit === 'seconds') {
            $this->addSeconds($value);
            return;
        }

        if (!isset($this->factors[$unit])) {
            throw new \Exception("'{$unit}' is not valid for this interval");
        }

        $this->add($this->factors[$unit][1], (int) ($value * $this->factors[$unit][0]));
    }

    /**
     * Subtract the specified amount of time from the interval (uses the __call method to allow subHours, for example)
     *
     * @param string $unit Any valid unit from the factors array, including the seconds array
     * @param int $value The number of units to subtract from the interval
     * @throws \Exception
     */
    protected function sub(string $unit, int $value)
    {
        if ($unit === 'seconds') {
            $this->subSeconds($value);
            return;
        }

        if (!isset($this->factors[$unit])) {
            throw new \Exception("'{$unit}' is not valid for this interval");
        }

        $this->sub($this->factors[$unit][1], (int) ($value * $this->factors[$unit][0]));
    }

    /**
     * Get the number of seconds since the last whole minute in the interval
     *
     * @return int
     */
    protected function getSeconds()
    {
        return $this->realSeconds % 60;
    }

    /**
     * Get the total number of seconds in the interval
     *
     * @return int
     */
    protected function getRealSeconds()
    {
        return $this->realSeconds;
    }


    /**
     * Gets the number of specified units
     *
     * For example, if the interval is 225 seconds, and the number of minutes is requested,
     * it will return 3 minutes.
     *
     * If the interval is 4000 seconds:
     *  - hours = 1
     *  - minutes = 6
     *  - seconds = 40
     *
     * @param string $unit
     * @return int
     * @throws \Exception
     */
    public function get(string $unit)
    {
        if ($unit === 'seconds') {
            return (int) $this->getSeconds();
        }

        if (!isset($this->factors[$unit])) {
            throw new \Exception("'{$unit}' is not valid for this interval");
        }

        $value = (int) ($this->getReal($unit));

        foreach ($this->factors as $f => $factor) {
            if ($factor[1] === $unit) {
                $value %= $factor[0];
                break;
            }
        }

        return (int) $value;
    }


    /**
     * Get the total number of specified units
     *
     * For example, an interval of 4000 seconds will return 66 minutes when requested
     *
     * @param string $unit
     * @return int
     * @throws \Exception
     */
    public function getReal(string $unit)
    {
        if ($unit === 'seconds') {
            return (int) $this->getRealSeconds();
        }

        if (!isset($this->factors[$unit])) {
            throw new \Exception("'{$unit}' is not valid for this interval");
        }

        return (int) ($this->getReal($this->factors[$unit][1]) / $this->factors[$unit][0]);
    }


    /**
     * Replace the factors and units used by this interval
     *
     * At least one factor must be multiplier for seconds, as this is the base unit of our interval
     *
     * @param array $factors
     * @return $this
     */
    public function setFactors(array $factors)
    {
        $this->factors = $factors;

        return $this;
    }


    /**
     * Add or replace a specific factor use by this interval
     *
     * @param string $name
     * @param array $factor [int $multiple, string $unit]
     * @return $this
     */
    public function setFactor(string $name, array $factor)
    {
        $this->factors[$name] = $factor;

        return $this;
    }

    /**
     * Get all the factors for this interval
     *
     * @return array
     */
    public function getFactors()
    {
        return $this->factors;
    }

    /**
     * Get a specific factor from this interval
     *
     * @param string $name
     * @return array|null
     */
    public function getFactor(string $name)
    {
        if (isset($this->factors[$name])) {
            return $this->factors[$name];
        }

        return null;
    }

    public function __get(string $name)
    {
        $func = "get" . ucfirst($name);
        if (method_exists($this, $func)) {
            return $this->$func();
        }

        if (substr($name, 0, 4) === "real") {
            return $this->getReal(lcfirst(substr($name, 4)));
        }

        return $this->get($name);
    }

    public function __call(string $name, array $args)
    {
        if (substr($name, 0, 3) === "add") {
            $this->add(lcfirst(substr($name, 3)), $args[0]);
            return $this;
        }

        if (substr($name, 0, 3) === "sub") {
            $this->sub(lcfirst(substr($name, 3)), $args[0]);
            return $this;
        }
    }
}

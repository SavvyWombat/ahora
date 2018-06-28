<?php


namespace SavvyWombat\Ahora;


/**
 * Class Interval
 * @package SavvyWombat\Ahora
 *
 * Time interval that allows simple addition/substraction for summing up and presenting
 *
 */
class Interval
{
    /**
     * @var int The number of seconds in this interval
     */
    protected $seconds = 0;


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
     * @param int $seconds
     */
    public function addSeconds(int $seconds)
    {
        $this->seconds += $seconds;
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

        $this->add($this->factors[$unit][1], $value * $this->factors[$unit][0]);
    }


    /**
     * Get the number of seconds since the last whole minute in the interval
     *
     * @return int
     */
    protected function getSeconds()
    {
        return $this->seconds % 60;
    }

    /**
     * Get the total number of seconds in the interval
     *
     * @return int
     */
    protected function getRealSeconds()
    {
        return $this->seconds;
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
        if (!isset($this->factors[$unit])) {
            throw new \Exception("'{$unit}' is not valid for this interval");
        }

        $value = (int) ($this->getReal($unit));

        reset($this->factors);
        while (!is_null(key($this->factors)) && key($this->factors) !== $unit) {
            next($this->factors);
        }
        next($this->factors);
        if (!is_null(key($this->factors))) {
            $value %= current($this->factors)[0];
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
            return $this->add(lcfirst(substr($name, 3)), $args[0]);
        }
    }
}
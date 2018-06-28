<?php


namespace SavvyWombat\Ahora;


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
    protected $seconds = 0;


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
     * Get the number of minutes since the last whole hour
     *
     * @return int
     */
    protected function getMinutes()
    {
        return $this->getRealMinutes() % 60;
    }

    /**
     * Get the total number of minutes in the interval
     *
     * @return int
     */
    protected function getRealMinutes()
    {
        return (int) ($this->seconds / 60);
    }


    /**
     * Get the number of hours since that last whole day
     *
     * @return int
     */
    protected function getHours()
    {
        return $this->getRealHours() % 24;
    }

    /**
     * Get the total number of hours in the interval
     *
     * @return int
     */
    protected function getRealHours()
    {
        return (int) ($this->getRealMinutes() / 60);
    }

    /**
     * Get the total number of days in the interval
     *
     * @return int
     */
    protected function getDays()
    {
        return (int) ($this->getRealHours() / 24);
    }



    public function __get(string $name)
    {
        $func = "get" . ucfirst($name);
        if (method_exists($this, $func)) {
            return $this->$func();
        }
    }



    public function __call(string $name, array $args)
    {
        if (substr($name, 0, 3) == "add") {
            $this->add(lcfirst(substr($name, 3)), $args[0]);
        }
    }
}
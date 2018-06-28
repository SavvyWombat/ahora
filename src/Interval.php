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



    /**
     * @param int $seconds
     */
    public function addSeconds(int $seconds)
    {
        $this->seconds += $seconds;
    }

    /**
     * @param int $minutes
     */
    public function addMinutes(int $minutes)
    {
        $this->addSeconds($minutes * 60);
    }

    /**
     * @param int $hours
     */
    public function addHours(int $hours)
    {
        $this->addMinutes($hours * 60);
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


    /**
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        $func = "get" . ucfirst($name);
        if (method_exists($this, $func)) {
            return $this->$func();
        }
    }
}
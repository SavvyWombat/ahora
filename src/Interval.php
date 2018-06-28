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
        $this->seconds += $minutes * 60;
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
     * Get the total number of hours in the interval
     *
     * @return int
     */
    protected function getHours()
    {
        return (int) ($this->getRealMinutes() / 60);
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
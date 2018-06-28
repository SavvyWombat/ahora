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
     * Get the number of minutes in the interval
     *
     * @return int
     */
    protected function getMinutes()
    {
        return (int) ($this->seconds / 60);
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
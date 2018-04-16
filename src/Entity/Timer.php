<?php
/**
 * Created by PhpStorm.
 * User: Listratsenka Stas
 * Date: 07.04.2018
 * Time: 13:34
 */

namespace App\Entity;


class Timer
{
    private $start;



    private $end;

    private $difference;

    private $duration;

    /**
     * @return mixed
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * @param mixed $start
     */
    public function setStart($start)
    {
        $this->start = $start;
    }

    /**
     * @return mixed
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * @param mixed $end
     */
    public function setEnd($end)
    {
        $this->end = $end;
    }

    /**
     * @return mixed
     */
    public function getDifference()
    {
        return $this->difference;
    }

    /**
     * @param mixed $difference
     */
    public function setDifference($difference)
    {
        $this->difference = $difference;
    }

    /**
     * @return mixed
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * @param mixed $duration
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;
    }


}